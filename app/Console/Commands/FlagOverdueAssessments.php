<?php

namespace App\Console\Commands;

use App\Models\TaxAssessment;
use App\Notifications\TaxAssessmentOverdue;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class FlagOverdueAssessments extends Command
{
    protected $signature = 'tax:flag-overdue {--dry-run : List affected assessments without updating records}';

    protected $description = 'Mark issued assessments as overdue and notify property owners';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $today = now();

        $assessments = TaxAssessment::with(['owner', 'payments', 'property'])
            ->where('status', 'issued')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', $today)
            ->get();

        $affected = 0;

        foreach ($assessments as $assessment) {
            $outstanding = max((float) $assessment->tax_amount - (float) $assessment->payments->sum('amount'), 0);
            if ($outstanding <= 0) {
                continue;
            }

            $affected++;

            if ($dryRun) {
                $this->line(sprintf('Would flag #%d (%s) for owner %s', $assessment->id, $assessment->property?->title, $assessment->owner?->display_name));
                continue;
            }

            $assessment->update(['status' => 'overdue']);

            if ($assessment->owner) {
                Notification::send($assessment->owner, new TaxAssessmentOverdue($assessment));
            }
        }

        $this->info("Flagged {$affected} assessments" . ($dryRun ? ' (dry run)' : '') . '.');

        return Command::SUCCESS;
    }
}
