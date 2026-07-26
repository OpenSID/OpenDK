<?php

namespace App\Rules;

use App\Models\User;
use App\Services\PasswordHistoryService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotInPasswordHistory implements ValidationRule
{
    private bool $skipWhenNoUser;

    public function __construct(private ?User $user = null)
    {
        $this->skipWhenNoUser = is_null($user);
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = $this->user ?? auth()->user();

        if (! $user) {
            return;
        }

        if (app(PasswordHistoryService::class)->isPasswordReused($user, $value)) {
            $fail(trans('passwords.history_found'));
        }
    }
}
