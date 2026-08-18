<?php

namespace App\Http\Livewire\Pengaturan;

use App\Services\ActivityLogService;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class LogAktivitasTable extends Component
{
    public $dateFrom;

    public $dateTo;

    public $userId;

    public $event;

    public $keyword;

    public $selectedActivityId = null;

    public $selectedActivity = null;

    protected $queryString = [
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'userId' => ['except' => ''],
        'event' => ['except' => ''],
        'keyword' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function render()
    {
        $activities = app(ActivityLogService::class)
            ->getFilteredActivities($this->dateFrom, $this->dateTo, $this->userId, $this->event, $this->keyword)
            ->latest()
            ->paginate(25);

        $users = \App\Models\User::orderBy('name')->get();
        $events = Activity::select('event')->distinct()->pluck('event');

        return view('livewire.pengaturan.log-aktivitas-table', compact('activities', 'users', 'events'));
    }

    public function showDetail(int $id): void
    {
        $this->selectedActivityId = $id;
        $this->selectedActivity = Activity::with('causer')->findOrFail($id);
        $this->showModal = true;
    }

    public function closeDetail(): void
    {
        $this->selectedActivityId = null;
        $this->selectedActivity = null;
        $this->showModal = false;
    }

    /**
     * Cek apakah aktivitas merupakan kegagalan (failed login atau error).
     */
    public function isActivityFailed($activity): bool
    {
        // Cek flag 'failed' di properties
        if (isset($activity->properties['failed']) && $activity->properties['failed']) {
            return true;
        }

        // Cek nama event yang mengandung kata 'gagal'
        if (in_array(strtolower($activity->event), ['login gagal', 'gagal'])) {
            return true;
        }

        return false;
    }

    public function resetFilters(): void
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->userId = '';
        $this->event = '';
        $this->keyword = '';
        $this->closeDetail();
    }
}
