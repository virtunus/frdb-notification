<?php

namespace Virtunus\FrdbNotification\Tests\Fixtures;

use Virtunus\FrdbNotification\FrdbChannel;
use Illuminate\Notifications\Notification;

class FakeFrdbChannel extends FrdbChannel
{
    public $data = null;
    public $reference = null;

    public function send($notifiable, Notification $notification)
    {
        $this->data = $this->getData($notifiable, $notification);
        
        $this->reference = $this->getFrdbReference($notifiable, $notification);

        return 'notification sent';
    }
}
