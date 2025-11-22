<?php

namespace App\Http\Controllers;

use App\Models\TaxAssessment;
use App\Models\TaxPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitizenTaxController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $assessmentsQuery = TaxAssessment::with(['property', 'payments' => fn ($query) => $query->latest('paid_at')])
            ->where('owner_id', $userId)
            ->orderByDesc('fiscal_year');

        $statusFilter = $request->string('status')->toString();
        if ($statusFilter) {
            $assessmentsQuery->where('status', $statusFilter);
        }

        $assessments = $assessmentsQuery->paginate(10)->withQueryString();

        $outstandingAssessments = TaxAssessment::where('owner_id', $userId)
            ->whereIn('status', ['issued', 'overdue'])
            ->get();

        $outstandingTotal = $outstandingAssessments->sum('tax_amount');
        $nextDueDate = $outstandingAssessments->filter(fn($item) => $item->due_date)->min('due_date');

        $recentPayments = TaxPayment::with(['assessment.property'])
            ->where('payer_id', $userId)
            ->latest('paid_at')
            ->take(10)
            ->get();

        return view('citizen.taxes.index', [
            'assessments' => $assessments,
            'outstandingTotal' => $outstandingTotal,
            'nextDueDate' => $nextDueDate,
            'statusFilter' => $statusFilter,
            'stripeEnabled' => filled(config('services.stripe.key')) && filled(config('services.stripe.secret')),
            'recentPayments' => $recentPayments,
        ]);
    }

    public function receipt(TaxPayment $taxPayment)
    {
        $this->authorizePayment($taxPayment);

        $taxPayment->load('assessment.property');

        return view('citizen.taxes.receipt', [
            'payment' => $taxPayment,
        ]);
    }

    protected function authorizePayment(TaxPayment $payment): void
    {
        if ($payment->payer_id !== Auth::id()) {
            abort(403);
        }
    }
}
