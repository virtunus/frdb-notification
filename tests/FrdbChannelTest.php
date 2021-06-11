<?php

namespace Virtunus\FrdbNotification\Tests;

use Illuminate\Notifications\AnonymousNotifiable;
use Virtunus\FrdbNotification\Tests\Fixtures\FakeFrdbChannel;
use Virtunus\FrdbNotification\Tests\Fixtures\SampleCustomizableNotification;
use Virtunus\FrdbNotification\Tests\Fixtures\SampleNotification;

class FrdbChannelTest extends TestCase
{
    /** @test */
    public function it_test_default_behaviour()
    {
        $notification = new SampleNotification();

        $channel = new FakeFrdbChannel();

        $notifiable = new AnonymousNotifiable();
        $notifiable->id = 1;
        $resp = $channel->send($notifiable, $notification);

        $this->assertEquals('notification sent', $resp);

        $this->assertEquals($channel->data, $notification->toArray($notifiable));

        $this->assertEquals('/Virtunus/FrdbNotification/Tests/Fixtures/SampleNotification/1', $channel->reference);
    }

    /** @test */
    public function it_test_customization_behaviour()
    {
        $notification = new SampleCustomizableNotification();

        $channel = new FakeFrdbChannel();

        $notifiable = new AnonymousNotifiable();

        $resp = $channel->send($notifiable, $notification);

        $this->assertEquals('notification sent', $resp);

        $this->assertEquals($channel->data, $notification->toFrdb($notifiable));
        
        $this->assertEquals($notification->frdbReference($notifiable), $channel->reference);
    }
}
