<?php

namespace App\Notifications;

use App\Models\TaxAssessment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaxAssessmentOverdue extends Notification
{
    use Queueable;

    public function __construct(private readonly TaxAssessment $assessment)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $outstanding = $this->outstandingAmount();

        return (new MailMessage)
            ->subject('Tax assessment overdue reminder')
            ->line('Your tax assessment for property: ' . ($this->assessment->property?->title ?? 'N/A') . ' is now overdue.')
            ->line('Outstanding amount: BDT ' . number_format($outstanding, 2))
            ->line('Due date: ' . optional($this->assessment->due_date)->format('M d, Y'))
            ->line('Please settle the amount to avoid penalties.')
            ->action('Review assessment', route('citizen.taxes.index'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'assessment_id' => $this->assessment->id,
            'property_id' => $this->assessment->property_id,
            'outstanding' => $this->outstandingAmount(),
        ];
    }

    protected function outstandingAmount(): float
    {
        $paid = $this->assessment->payments()->sum('amount');

        return max((float) $this->assessment->tax_amount - (float) $paid, 0);
    }
}
