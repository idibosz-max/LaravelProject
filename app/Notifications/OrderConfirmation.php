<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmation extends Notification
{
    use Queueable;

    public function __construct(public Order $order)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $orderNumber = str_pad($this->order->id, 6, '0', STR_PAD_LEFT);

        return (new MailMessage)
            ->subject("DIB Productions – Order #{$orderNumber} Confirmed")
            ->greeting("Hello {$this->order->shipping_name}!")
            ->line("Thank you for your order from DIB Productions.")
            ->line("**Order Number:** #{$orderNumber}")
            ->line("**Total:** $" . number_format($this->order->total, 2))
            ->line("**Shipping to:** {$this->order->shipping_address}, {$this->order->shipping_city}, {$this->order->shipping_country}")
            ->line("We'll notify you once your order ships.")
            ->action('Visit DIB Productions', url('/'))
            ->salutation("— The DIB Productions Team");
    }
}
