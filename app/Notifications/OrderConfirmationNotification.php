<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmationNotification extends Notification
{
    use Queueable;

    public $session;

    /**
     * Create a new notification instance.
     */
    public function __construct($session)
    {
        $this->session = $session;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('https://dashboard.stripe.com/test/payments/' . $this->session->payment_intent);
        return (new MailMessage)
            ->subject('Nouvelle commande de ' . $this->session->customer_details->name)
            ->line('Vous avez reçu une nouvelle commande.')

            ->line('Adresse de livraison :')
            ->line($this->session->customer_details->address->line1 . ' ' .  $this->session->customer_details->address->postal_code . ' ' . $this->session->customer_details->address->city)
            ->line('Détails de la commmande :')
            ->action('Voir la commande', $url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
