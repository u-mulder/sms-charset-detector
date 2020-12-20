<?php

declare(strict_types=1);

namespace Um\CharsetDetector;

class BasicCharsetDetector implements CharsetDetectorInterface
{
    /**
     * Basic gsm characters
     *
     * @see https://en.wikipedia.org/wiki/GSM_03.38
     */
    protected const GSM_BASIC_SET = [
        '@', 'Δ', ' ', '0', '¡', 'P', '¿', 'p',
        '£', '_', '!', '1', 'A', 'Q', 'a', 'q',
        '$', 'Φ', '"', '2', 'B', 'R', 'b', 'r',
        '¥', 'Γ', '#', '3', 'C', 'S', 'c', 's',
        'è', 'Λ', '¤', '4', 'D', 'T', 'd', 't',
        'é', 'Ω', '%', '5', 'E', 'U', 'e', 'u',
        'ù', 'Π', '&', '6', 'F', 'V', 'f', 'v',
        'ì', 'Ψ', '\'', '7', 'G', 'W', 'g', 'w',
        'ò', 'Σ', '(', '8', 'H', 'X', 'h', 'x',
        'Ç', 'Θ', ')', '9', 'I', 'Y', 'i', 'y',
        "\n", 'Ξ', '*', ':', 'J', 'Z', 'j', 'z',
        'Ø', "\x1B", '+', ';', 'K', 'Ä', 'k', 'ä',
        'ø', 'Æ', ',', '<', 'L', 'Ö', 'l', 'ö',
        "\r", 'æ', '-', '=', 'M', 'Ñ', 'm', 'ñ',
        'Å', 'ß', '.', '>', 'N', 'Ü', 'n', 'ü',
        'å', 'É', '/', '?', 'O', '§', 'o', 'à',
    ];

    /** Extension set characters, each occupies two positions in sms */
    protected const GSM_EXTENSION_SET = [
        '|', '^', '€', '{', '}', '[', '~', ']', '\\',
    ];

    /** Use extension set characters or not when detecting the charset */
    protected $includeExtensionSet = true;

    /** Current charset, stores either all characters or ones from basic set only */
    protected $currentCharset = [];

    public function __construct(bool $includeExtensionSet)
    {
        $this->setIncludeExtensionSet($includeExtensionSet);
    }

    public function setIncludeExtensionSet(bool $includeExtensionSet): self
    {
        $this->includeExtensionSet = $includeExtensionSet;

        $this->currentCharset = $this->includeExtensionSet
            ? static::GSM_BASIC_SET
            : array_merge(static::GSM_BASIC_SET, static::GSM_EXTENSION_SET);

        return $this;
    }

    public function getIncludeExtensionSet(): bool
    {
        return $this->includeExtensionSet;
    }

    public function detectCharset(string $message): string
    {
        if ('' === $message) {
            return static::GSM_CHARSET;
        }

        $charsCount = \mb_strlen($message, 'UTF-8');
        for ($i = 0; $i < $charsCount; ++$i) {
            $currentChar = \mb_substr($message, $i, 1);
            if (!in_array($currentChar, $this->currentCharset, true)) {
                return static::UCS_CHARSET;
            }
        }

        return static::GSM_CHARSET;
    }

    public function isGsmCharset(string $message): bool
    {
        return static::GSM_CHARSET === $this->detectCharset($message);
    }
}
