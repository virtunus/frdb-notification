<?php

namespace Virtunus\FrdbNotification\Tests\Fixtures;

class SampleCustomizableNotification extends SampleNotification
{
    public function frdbStoreMethod(): string
    {
        return 'push';
    }

    public function toFrdb($notifiable)
    {
        return [
            'message' => 'toFrdb method was called',
        ];
    }

    public function frdbReference($notifiable): string
    {
        return 'ref';
    }
}
