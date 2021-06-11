<?php

namespace Virtunus\FrdbNotification\Tests\Fixtures;

use Illuminate\Notifications\Notification;
use Virtunus\FrdbNotification\FrdbChannel;

class SampleNotification extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [FrdbChannel::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'toArray method was called',
        ];
    }
}
