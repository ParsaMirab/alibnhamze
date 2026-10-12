<?php

namespace App\Services;

use App\Enums\OtpType;
use App\Exceptions\OtpSendingFailedException;
use App\Models\Otps;
use App\Services\Sms\SmsProvider;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

/**
 * Single entry point for creating, scoping and consuming OTP codes.
 *
 * Every operation is scoped by {@see OtpType} so a login code can never
 * verify a pre-registration request (and the other way around).
 */
class OtpService
{
    public function __construct(private readonly SmsProvider $sms) {}

    /**
     * Generate a cryptographically secure numeric code.
     */
    public function generateCode(int $length = 6): string
    {
        $max = (10 ** $length) - 1;

        return str_pad((string) random_int(0, $max), $length, '0', STR_PAD_LEFT);
    }

    /**
     * Invalidate every still-usable OTP for a phone number and purpose.
     */
    public function invalidate(string $phone, OtpType $type): int
    {
        return Otps::query()
            ->where('phone', $phone)
            ->where('type', $type->value)
            ->whereNull('used_at')
            ->update(['used_at' => Carbon::now()]);
    }

    /**
     * Persist a new OTP row without delivering it yet.
     */
    public function store(
        string $phone,
        OtpType $type,
        string $code,
        CarbonInterface $expiresAt,
        ?int $userId = null,
    ): Otps {
        return Otps::create([
            'user_id' => $userId,
            'phone' => $phone,
            'code' => $code,
            'type' => $type->value,
            'expires_at' => $expiresAt,
        ]);
    }

    /**
     * Find a still-usable OTP matching the phone number, purpose and code.
     */
    public function find(string $phone, OtpType $type, string $code): ?Otps
    {
        return Otps::query()
            ->where('phone', $phone)
            ->where('type', $type->value)
            ->where('code', $code)
            ->whereNull('used_at')
            ->where('expires_at', '>', Carbon::now())
            ->latest('id')
            ->first();
    }

    /**
     * The most recently issued OTP for a phone number and purpose.
     */
    public function latest(string $phone, OtpType $type): ?Otps
    {
        return Otps::query()
            ->where('phone', $phone)
            ->where('type', $type->value)
            ->latest('id')
            ->first();
    }

    /**
     * Mark an OTP as consumed so it can never be replayed.
     */
    public function consume(Otps $otp): void
    {
        $otp->forceFill(['used_at' => Carbon::now()])->save();
    }

    /**
     * Generate, store and deliver a new OTP.
     *
     * Existing active codes for the same purpose are invalidated first, so
     * only the newest code issued to a phone number keeps working.
     *
     * @param  array{code?: string, length?: int, ttl_minutes?: int, user_id?: int|null}  $options
     *
     * @throws OtpSendingFailedException when the provider did not accept the message.
     */

    public function issue(string $phone, OtpType $type, array $options = []): Otps
    {
        // Fixed OTP only for local pre-registration testing.
        $isTestMode = app()->environment('local')
            && $type === OtpType::PRE_REGISTER;

        $this->invalidate($phone, $type);

        $code = $isTestMode
            ? '123456'
            : ($options['code'] ?? $this->generateCode($options['length'] ?? 6));

        $ttlMinutes = $options['ttl_minutes'] ?? 2;

        // Always persist the OTP in the database.
        $otp = $this->store(
            $phone,
            $type,
            $code,
            Carbon::now()->addMinutes($ttlMinutes),
            $options['user_id'] ?? null,
        );

        // Local testing: OTP is stored, but no real SMS is sent.
        if ($isTestMode) {
            return $otp;
        }

        $message = $this->message($type, $code, $ttlMinutes);

        if (! $this->sms->send($phone, $message)) {
            $otp->delete();

            throw new OtpSendingFailedException;
        }

        return $otp;
    }

    /**
     * Build the Persian SMS body.
     */
    protected function message(OtpType $type, string $code, int $ttlMinutes): string
    {
        return "کد تأیید {$type->label()} هنرستان علی بن حمزه: {$code}\nمهلت استفاده: {$ttlMinutes} دقیقه";
    }
}
