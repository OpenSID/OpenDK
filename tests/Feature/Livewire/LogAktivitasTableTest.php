<?php

use App\Http\Livewire\Pengaturan\LogAktivitasTable;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('renders log aktivitas table component', function () {
    $component = Livewire::test(LogAktivitasTable::class);
    $component->assertStatus(200);
    $component->assertSee('Riwayat Aktivitas');
});

it('filters activities when filter values change', function () {
    $user1 = User::factory()->create(['name' => 'Alice']);
    $user2 = User::factory()->create(['name' => 'Bob']);

    $activity1 = Activity::create([
        'log_name' => 'default',
        'causer_id' => $user1->id,
        'causer_type' => User::class,
        'event' => 'login',
        'description' => 'Alice logged in',
        'created_at' => now()->subDays(1),
    ]);

    $activity2 = Activity::create([
        'log_name' => 'default',
        'causer_id' => $user2->id,
        'causer_type' => User::class,
        'event' => 'logout',
        'description' => 'Bob logged out',
        'created_at' => now()->subDays(2),
    ]);

    $component = Livewire::test(LogAktivitasTable::class)
        ->set('userId', $user1->id)
        ->assertSee('Alice logged in')
        ->assertDontSee('Bob logged out');
});

it('opens detail modal when detail button is clicked', function () {
    $user = User::factory()->create();
    $activity = Activity::create([
        'log_name' => 'default',
        'causer_id' => $user->id,
        'causer_type' => User::class,
        'event' => 'login',
        'description' => 'Test login',
        'properties' => ['ip_address' => '127.0.0.1'],
    ]);

    $component = Livewire::test(LogAktivitasTable::class)
        ->call('showDetail', $activity->id);

    $component->assertSet('selectedActivityId', $activity->id);
    $component->assertSee('Detail Aktivitas');
    $component->assertSee('127.0.0.1');
});

it('closes detail modal when close button is clicked', function () {
    $user = User::factory()->create();
    $activity = Activity::create([
        'log_name' => 'default',
        'causer_id' => $user->id,
        'causer_type' => User::class,
        'event' => 'login',
        'description' => 'Test login',
    ]);

    $component = Livewire::test(LogAktivitasTable::class)
        ->call('showDetail', $activity->id)
        ->call('closeDetail');

    $component->assertSet('selectedActivityId', null);
    $component->assertSet('selectedActivity', null);
});
