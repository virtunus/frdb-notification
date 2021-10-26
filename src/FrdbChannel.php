<?php

namespace Virtunus\FrdbNotification;

use Exception;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use Throwable;

/**
 * Firebase Realtime Database channel
 */
class FrdbChannel
{
    /**
     * Send the notification data
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (config('firebase-channel.enabled') != true) {
            return;
        }

        try {
            $ref = $this->getFrdbReference($notifiable, $notification);

            $data = $this->getData($notifiable, $notification);

            $database = app('firebase.database');

            $storeMethod = 'set';

            if (method_exists($notification, 'frdbStoreMethod')) {
                $storeMethod = $notification->frdbStoreMethod($notifiable);
            }
            // $database->getReference("$ref")->$storeMethod($data);
            call_user_func([$database->getReference("$ref"), $storeMethod], $data);
        } catch (Exception $ex) {
            $this->failedNotification($notifiable, $notification, $ex);
            throw $ex;
        }
    }

    /**
     * Get the payload for the notification
     *
     * @param $mixed $notifiable
     * @param Notification $notification
     * @return $mixed
     */
    protected function getData($notifiable, Notification $notification)
    {
        if (method_exists($notification, 'toFrdb')) {
            return $notification->toFrdb($notifiable);
        }
        
        return $notification->toArray($notifiable);
    }

    /**
     * Get the reference for firebase realtime database
     *
     * @param Notification $notification
     * @return string
     */
    protected function getFrdbReference($notifiable, Notification $notification): string
    {
        if (method_exists($notification, 'frdbReference')) {
            return $notification->frdbReference($notifiable) ?: null;
        }

        $ref = str_replace('\\', '/', get_class($notification));

        return '/' . $ref . '/' . ($notifiable->id ?? '');
    }

    /**
     * Dispatch failed event.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @param \Throwable $exception
     * @return array|null
     */
    protected function failedNotification($notifiable, Notification $notification, Throwable $exception)
    {
        return event(new NotificationFailed(
            $notifiable,
            $notification,
            self::class,
            [
                'message' => $exception->getMessage(),
                'exception' => $exception,
            ]
        ));
    }
}
