<?php

declare(strict_types=1);

namespace Um\CharsetDetector;

interface CharsetDetectorInterface
{
    public const GSM_CHARSET = 'gsm';
    public const UCS_CHARSET = 'ucs';

    public function detectCharset(string $message): string;
}
