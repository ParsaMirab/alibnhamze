<?php

namespace Tests\Support;

use App\Services\Sms\SmsProvider;

/**
 * In-memory SMS provider used by the test suite: no real message is sent.
 */
class FakeSmsProvider implements SmsProvider
{
    /**
     * Delivered messages, newest last.
     *
     * @var array<int, array{phone: string, message: string}>
     */
    public array $messages = [];

    /**
     * When true every send is rejected, simulating a gateway outage.
     */
    public bool $shouldFail = false;

    /**
     * {@inheritDoc}
     */
    public function send(string $phone, string $message): bool
    {
        if ($this->shouldFail) {
            return false;
        }

        $this->messages[] = [
            'phone' => $phone,
            'message' => $message,
        ];

        return true;
    }

    /**
     * The six-digit codes found in the delivered messages.
     *
     * @return array<int, string>
     */
    public function codes(): array
    {
        return array_values(array_filter(array_map(function (array $message): ?string {
            return preg_match('/\b(\d{6})\b/', $message['message'], $matches) ? $matches[1] : null;
        }, $this->messages)));
    }

    /**
     * The most recently generated code.
     */
    public function lastCode(): ?string
    {
        $codes = $this->codes();

        return $codes === [] ? null : end($codes);
    }
}
