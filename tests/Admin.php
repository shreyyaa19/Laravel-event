<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Admin;

class AdminEventsTest extends TestCase
{
    public function test_show_events_displays_events()
    {
        $admin = factory(App\Models\Admin::class)->create(['account_id' => 1]);

        $event1 = factory(App\Models\Event::class)->create([
            'account_id'   => $admin->account_id,
            'admin_id' => $admin->id,
            'user_id'      => $this->test_user->id,
        ]);

        $event2 = factory(App\Models\Event::class)->create([
            'account_id'   => $admin->account_id,
            'admin_id' => $admin->id,
            'user_id'      => $this->test_user->id,
        ]);

        $this->actingAs($this->test_user)
            ->visit(route('showAdminEvents', ['admin_id' => $admin->id]))
            ->see($event1->title)
            ->see($event2->title)
            ->see('2 events');
            
    }
}
