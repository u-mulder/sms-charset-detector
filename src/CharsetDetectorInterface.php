<?php

declare(strict_types=1);

namespace Um/CharsetDetector;

interface CharsetDetectorInterface
{
	protected const GSM_CHARSET = 'gsm';
	protected const UCS_CHARSET = 'ucs';
	
	public function detectCharset(string $message): string;
}
