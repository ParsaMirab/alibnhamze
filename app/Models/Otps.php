<?php

namespace App\Models;

use App\Enums\OtpType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'phone', 'code', 'type', 'expires_at', 'used_at'])]
#[Hidden([])]
class Otps extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => OtpType::class,
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    /**
     * Limit the query to a single OTP purpose.
     */
    public function scopeOfType(Builder $query, OtpType $type): Builder
    {
        return $query->where('type', $type->value);
    }

    /**
     * Limit the query to codes that can still be used.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('used_at')->where('expires_at', '>', now());
    }

    /**
     * Whether the code's validity window has passed.
     */
    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    /**
     * Whether the code has already been used.
     */
    public function isConsumed(): bool
    {
        return $this->used_at !== null;
    }
}
