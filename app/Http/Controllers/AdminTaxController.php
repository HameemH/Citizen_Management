<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\TaxAssessment;
use App\Models\TaxPayment;
use App\Services\TaxAssessmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminTaxController extends Controller
{
    public function __construct(private readonly TaxAssessmentService $assessmentService)
    {
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'fiscal_year', 'q']);

        $assessments = TaxAssessment::with(['property.owner'])
            ->when($request->filled('status'), fn($query) => $query->where('status', $request->status))
            ->when($request->filled('fiscal_year'), fn($query) => $query->where('fiscal_year', $request->fiscal_year))
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->whereHas('property', function ($sub) use ($request) {
                        $sub->where('title', 'like', '%' . $request->q . '%')
                            ->orWhere('city', 'like', '%' . $request->q . '%');
                    })->orWhereHas('owner', fn($sub) => $sub->where('display_name', 'like', '%' . $request->q . '%'));
                });
            })
            ->latest('fiscal_year')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'issued' => TaxAssessment::where('status', 'issued')->count(),
            'overdue' => TaxAssessment::where('status', 'overdue')->count(),
            'outstanding_total' => TaxAssessment::outstanding()->sum('tax_amount'),
            'paid' => TaxAssessment::where('status', 'paid')->count(),
        ];

        return view('admin.taxes.index', compact('assessments', 'filters', 'stats'));
    }

    public function create()
    {
        $properties = Property::with('owner')->active()->orderBy('title')->get();
        $defaultYear = sprintf('%d-%d', now()->year, now()->year + 1);

        return view('admin.taxes.create', compact('properties', 'defaultYear'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'fiscal_year' => 'required|string|max:9',
            'assessed_value' => 'nullable|numeric|min:0',
            'note' => 'nullable|string|max:500',
        ]);

        $property = Property::findOrFail($data['property_id']);

        $assessment = $this->assessmentService->generate($property, $data['fiscal_year'], $data['assessed_value'] ?? null);
        if (!empty($data['note'])) {
            $assessment->notes = trim($assessment->notes . PHP_EOL . $data['note']);
            $assessment->save();
        }

        return redirect()->route('admin.taxes.show', $assessment)->with('status', 'Assessment generated.');
    }

    public function show(TaxAssessment $taxAssessment)
    {
        $taxAssessment->load(['property.owner', 'payments.payer', 'payments.recorder']);

        return view('admin.taxes.show', [
            'assessment' => $taxAssessment,
            'paymentTotal' => $taxAssessment->payments->sum('amount'),
        ]);
    }

    public function issue(Request $request, TaxAssessment $taxAssessment)
    {
        $data = $request->validate([
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $taxAssessment->update([
            'status' => 'issued',
            'due_date' => $data['due_date'] ?? $taxAssessment->due_date ?? Carbon::now()->addMonths(2),
            'issued_at' => now(),
            'notes' => $data['notes'] ?? $taxAssessment->notes,
        ]);

        return back()->with('status', 'Assessment issued.');
    }

    public function recordPayment(Request $request, TaxAssessment $taxAssessment)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'method' => 'nullable|string|max:50',
            'reference' => 'nullable|string|max:100',
            'paid_at' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        TaxPayment::create([
            'tax_assessment_id' => $taxAssessment->id,
            'payer_id' => $taxAssessment->owner_id,
            'recorded_by' => auth()->id(),
            'amount' => $data['amount'],
            'method' => $data['method'] ?? 'manual',
            'reference' => $data['reference'],
            'paid_at' => array_key_exists('paid_at', $data) && $data['paid_at']
                ? Carbon::parse($data['paid_at'])
                : now(),
            'notes' => $data['notes'] ?? null,
        ]);

        $taxAssessment->refresh();
        $taxAssessment->markPaidIfSettled();

        return back()->with('status', 'Payment recorded.');
    }
}
