<?php

namespace App\Notifications;

use App\Models\Property;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PropertyTransferCompleted extends Notification
{
    use Queueable;

    public function __construct(private readonly Property $property, private readonly ?User $previousOwner = null)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Property Transfer Completed')
            ->line('The transfer for ' . $this->property->title . ' has been completed.')
            ->line('New owner: ' . ($this->property->owner?->display_name ?? 'Unassigned'))
            ->line('Property address: ' . $this->property->address_line . ', ' . $this->property->city)
            ->line('Thank you for keeping the property registry up to date.');

        if ($this->previousOwner) {
            $message->line('Previous owner: ' . $this->previousOwner->display_name);
        }

        return $message;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'property_id' => $this->property->id,
            'new_owner_id' => $this->property->owner_id,
            'previous_owner_id' => $this->previousOwner?->id,
        ];
    }
}
