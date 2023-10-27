<?php

namespace NotificationChannels\Sendberry;

use Illuminate\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use NotificationChannels\Sendberry\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;
use Exception;

class SendberryChannel
{
    /**
     * @var TurboSmsApi
     */
    protected $smsApi;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * SendberryChannel constructor.
     * @param TurboSmsApi $smsApi
     * @param Dispatcher $events
     */
    public function __construct(SendberryApi $smsApi, Dispatcher $events)
    {
        $this->smsApi = $smsApi;
        $this->events = $events;
    }
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     *
     * @throws CouldNotSendNotification
     *
     * @return array|null
     */
    public function send($notifiable, Notification $notification)
    {
        try {
            $recipient = $this->getRecipient($notifiable);
            $message = $notification->toSendberry($notifiable);

            if (is_string($message)) {
                $message = new SendberryMessage($message);
            }
            if (! $message instanceof SendberryMessage) {
                throw CouldNotSendNotification::invalidMessageObject($message);
            }

            return $this->smsApi->sendMessage($recipient, $message);

        } catch (Exception $exception) {
            $event = new NotificationFailed($notifiable, $notification, 'Sendberry', ['message' => $exception->getMessage(), 'exception' => $exception]);

            if (function_exists('event')) { // Use event helper when possible to add Lumen support
                event($event);
            } else {
                $this->events->fire($event);
            }
        }
    }

    protected function getRecipient($notifiable)
    {
        if ($notifiable->routeNotificationFor('Sendberry')) {
            return $notifiable->routeNotificationFor('Sendberry');
        }

        if (isset($notifiable->phone)) {
            return $notifiable->phone;
        }

        throw CouldNotSendNotification::invalidReceiver();
    }
}
