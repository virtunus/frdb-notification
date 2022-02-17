<?php

namespace Virtunus\FrdbNotification\Tests;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Kreait\Firebase\Database;
use Kreait\Firebase\Database\Reference;
use Mockery\MockInterface;
use Virtunus\FrdbNotification\FrdbChannel;
use Virtunus\FrdbNotification\Tests\Fixtures\SampleNotification;
use Virtunus\FrdbNotification\Tests\Fixtures\SampleCustomizableNotification;

class FrdbNotificationTest extends TestCase
{
    /** @test */
    public function it_does_not_send_notification_if_disabled_from_config()
    {
        config(['firebase-channel.enabled' => false]);

        $notifiable  = new AnonymousNotifiable();
        Notification::send($notifiable, new SampleNotification());
        $this->assertTrue(true);
    }

    /** @test */
    public function it_expects_send_method_of_channel_is_called()
    {
        $mock = $this->mock(FrdbChannel::class, function (MockInterface $mock) {
            $mock->shouldReceive('send')->once();
        });

        $notifiable  = new AnonymousNotifiable();

        Notification::send($notifiable, new SampleNotification());
    }

    /** @test-1 */
    public function it_expects_notification_data_sent_to_frdb_by_default_set_method()
    {
        $ref = $this->mock(Reference::class, function (MockInterface $mock) {
            $mock->shouldReceive('set')->once();
        });

        $mock = $this->mock(Database::class, function (MockInterface $mock) use ($ref) {
            $mock->shouldReceive('getReference')->once()->andReturn($ref);
        });

        $notifiable  = new AnonymousNotifiable();
        $notifiable->id = 1;

        Notification::send($notifiable, new SampleNotification());
    }

    /** @test-1 */
    public function it_expects_notification_data_sent_to_frdb_by_custom_push_method()
    {
        $ref = $this->mock(Reference::class, function (MockInterface $mock) {
            $mock->shouldReceive('push')->once();
        });

        $mock = $this->mock(Database::class, function (MockInterface $mock) use ($ref) {
            $mock->shouldReceive('getReference')->once()->andReturn($ref);
        });

        $notifiable  = new AnonymousNotifiable();
        $notifiable->id = 1;

        Notification::send($notifiable, new SampleCustomizableNotification());
    }

    /** @test-1 */
    public function it_expects_customizable_methods_are_called()
    {
        $ref = $this->mock(Reference::class, function (MockInterface $mock) {
            $mock->shouldReceive('push')->once();
        });

        $mock = $this->mock(Database::class, function (MockInterface $mock) use ($ref) {
            $mock->shouldReceive('getReference')->once()->andReturn($ref);
        });

        $notifiable  = new AnonymousNotifiable();
        $notifiable->id = 1;

        $notification = $this->mock(SampleCustomizableNotification::class, function (MockInterface $mock) {
            $mock->shouldReceive([
                'via' => FrdbChannel::class,
                'frdbStoreMethod' => 'push',
                'frdbReference' => 'ref',
                'toFrdb' => 'test'
            ])->once();
        });

        Notification::send($notifiable, $notification);
    }
}
