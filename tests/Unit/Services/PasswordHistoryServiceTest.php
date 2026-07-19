<?php

use App\Models\PasswordHistory;
use App\Models\User;
use App\Services\PasswordHistoryService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->service = app(PasswordHistoryService::class);
    $this->password = 'CurrentPass123!';
    $this->user = User::factory()->create([
        'password' => Hash::make($this->password),
    ]);
});

it('can instantiate the service', function () {
    expect($this->service)->toBeInstanceOf(PasswordHistoryService::class);
});

it('has max history constant set to 10', function () {
    expect(PasswordHistoryService::MAX_HISTORY)->toBe(10);
});

describe('isPasswordReused', function () {
    it('returns true when password matches current password', function () {
        $reused = $this->service->isPasswordReused($this->user, $this->password);

        expect($reused)->toBeTrue();
    });

    it('returns true when password matches a history entry', function () {
        PasswordHistory::factory()->create([
            'user_id' => $this->user->id,
            'password' => Hash::make('OldPassword123!'),
        ]);

        $reused = $this->service->isPasswordReused($this->user, 'OldPassword123!');

        expect($reused)->toBeTrue();
    });

    it('returns false when password is not in current or history', function () {
        $reused = $this->service->isPasswordReused($this->user, 'BrandNewPassword123!');

        expect($reused)->toBeFalse();
    });

    it('returns false when user has no history entries', function () {
        expect($this->user->passwordHistories()->count())->toBe(0);

        $reused = $this->service->isPasswordReused($this->user, 'AnotherNewPass123!');

        expect($reused)->toBeFalse();
    });

    it('returns true when any of multiple history entries match', function () {
        $passwords = ['First123!', 'Second123!', 'Third123!', 'Fourth123!'];
        foreach ($passwords as $pw) {
            PasswordHistory::factory()->create([
                'user_id' => $this->user->id,
                'password' => Hash::make($pw),
            ]);
        }

        $reused = $this->service->isPasswordReused($this->user, 'Third123!');

        expect($reused)->toBeTrue();
    });

    it('does not match against other users passwords', function () {
        $otherUser = User::factory()->create();
        PasswordHistory::factory()->create([
            'user_id' => $otherUser->id,
            'password' => Hash::make('OtherUserPass123!'),
        ]);

        $reused = $this->service->isPasswordReused($this->user, 'OtherUserPass123!');

        expect($reused)->toBeFalse();
    });
});

describe('storeCurrentPassword', function () {
    it('creates a history record for the current password', function () {
        expect($this->user->passwordHistories()->count())->toBe(0);

        $this->service->storeCurrentPassword($this->user);

        expect($this->user->passwordHistories()->count())->toBe(1);
        $history = $this->user->passwordHistories()->first();
        expect(Hash::check($this->password, $history->password))->toBeTrue();
    });

    it('preserves the current password in the users table', function () {
        $this->service->storeCurrentPassword($this->user);

        expect(Hash::check($this->password, $this->user->fresh()->password))->toBeTrue();
    });

    it('stores already-hashed passwords correctly', function () {
        $this->service->storeCurrentPassword($this->user);

        $history = $this->user->passwordHistories()->first();
        expect($history->password)->toBe($this->user->getOriginal('password'));
    });
});

describe('prune', function () {
    it('does nothing when history count is within limit', function () {
        foreach (range(1, 5) as $i) {
            PasswordHistory::factory()->create([
                'user_id' => $this->user->id,
                'password' => Hash::make("Pass{$i}123!"),
            ]);
        }

        $this->service->prune($this->user);

        expect($this->user->passwordHistories()->count())->toBe(5);
    });

    it('trims to max history when exceeded', function () {
        foreach (range(1, 15) as $i) {
            PasswordHistory::factory()->create([
                'user_id' => $this->user->id,
                'password' => Hash::make("Pass{$i}123!"),
                'created_at' => now()->addSeconds($i),
            ]);
        }

        expect($this->user->passwordHistories()->count())->toBe(15);

        $this->service->prune($this->user);

        expect($this->user->passwordHistories()->count())->toBe(10);
    });

    it('removes oldest records when pruning', function () {
        foreach (range(1, 12) as $i) {
            PasswordHistory::factory()->create([
                'user_id' => $this->user->id,
                'password' => Hash::make("Pass{$i}123!"),
                'created_at' => now()->addMinutes($i),
            ]);
        }

        $firstId = $this->user->passwordHistories()->orderBy('created_at')->first()->id;

        $this->service->prune($this->user);

        expect($this->user->passwordHistories()->find($firstId))->toBeNull();
    });

    it('preserves the 10 most recent entries', function () {
        foreach (range(1, 12) as $i) {
            PasswordHistory::factory()->create([
                'user_id' => $this->user->id,
                'password' => Hash::make("Pass{$i}123!"),
                'created_at' => now()->addMinutes($i),
            ]);
        }

        $this->service->prune($this->user);

        $remaining = $this->user->passwordHistories()->orderBy('created_at', 'desc')->get();
        expect($remaining->count())->toBe(10);
        expect(Hash::check('Pass3123!', $remaining->last()->password))->toBeTrue();
        expect(Hash::check('Pass12123!', $remaining->first()->password))->toBeTrue();
    });
});

describe('storeCurrentPassword integration with prune', function () {
    it('calls prune automatically after storing', function () {
        foreach (range(1, 10) as $i) {
            PasswordHistory::factory()->create([
                'user_id' => $this->user->id,
                'password' => Hash::make("Pass{$i}123!"),
            ]);
        }

        expect($this->user->passwordHistories()->count())->toBe(10);

        $this->service->storeCurrentPassword($this->user);

        expect($this->user->passwordHistories()->count())->toBe(10);
    });

    it('stores password even when history is full', function () {
        foreach (range(1, 10) as $i) {
            PasswordHistory::factory()->create([
                'user_id' => $this->user->id,
                'password' => Hash::make("Pass{$i}123!"),
                'created_at' => now()->subMinutes(10)->addSeconds($i),
            ]);
        }

        $this->service->storeCurrentPassword($this->user);

        expect($this->user->passwordHistories()->count())->toBe(10);

        $newest = $this->user->passwordHistories()
            ->orderBy('created_at', 'desc')
            ->first();
        expect(Hash::check($this->password, $newest->password))->toBeTrue();
    });
});
