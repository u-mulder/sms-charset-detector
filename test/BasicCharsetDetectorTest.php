<?php

declare(strict_types=1);

namespace Um\CharsetDetector\Tests;

use PHPUnit\Framework\TestCase;
use Um\CharsetDetector\BasicCharsetDetector;
use Um\CharsetDetector\CharsetDetectorInterface;

class BasicCharsetDetectorTest extends TestCase 
{
    /**
     * @dataProvider provideDetectCharsetMessages
     */
    public function testDetectCharset(
        string $message,
        bool $includeExtensionSet,
        string $expectedCharset
    ): void {
		$detector = $this->getCharsetDetector($includeExtensionSet);
		
		$this->assertSame(
			$expectedCharset,
			$detector->detectCharset($message)
		);
    }

    /**
     * @dataProvider provideIsGsmCharsetMessages
     */
    public function testIsGsmCharset(
        string $message,
        bool $includeExtensionSet,
        bool $isGsmCharset
    ): void {
		$detector = $this->getCharsetDetector($includeExtensionSet);

		$this->assertSame(
			$isGsmCharset,
			$detector->isGsmCharset($message)
		);
    }
    
    /**
     * @dataProvider provideTrueFalse
     */
    public function testGetGsmCharsetAsJson(bool $includeExtensionSet): void
    {
        $detector = $this->getCharsetDetector($includeExtensionSet);
        
        $jsonStr = $detector->getGsmCharsetAsJson(); 

		$this->assertIsString($jsonStr, 'Returned data must be of type "string"');
        $this->assertGreaterThan(
            0, 
            \mb_strlen($jsonStr),
            'Length of returned string must be greater than 0'
        );
        
    }

    public function provideDetectCharsetMessages(): array
    {
        return self::getMessagesData();
    }

    public function provideIsGsmCharsetMessages(): array
    {
        $data = self::getMessagesData();
        foreach ($data as &$item) {
            $item[2] = CharsetDetectorInterface::GSM_CHARSET === $item[2];
        }

        return $data;
    }

    public function provideTrueFalse(): array
    {
        return [
            'With extension set' => [true],
            'Without extension set' => [false]
        ];
    }
    
    protected function getCharsetDetector(bool $includeExtensionSet): BasicCharsetDetector
    {
        return new BasicCharsetDetector($includeExtensionSet);
    }

    protected static function getMessagesData(): array
    {
        return [
            'Empty string is valid gsm-1' => [
                '',
                true,
                CharsetDetectorInterface::GSM_CHARSET,
            ],
            'Empty string is valid gsm-2' => [
                '',
                false,
                CharsetDetectorInterface::GSM_CHARSET,
            ],
            'Plain ASCII symbols with extension set' => [
                'Black Friday in our web-shop! Sales 20% on everything! Visit shop.tld/?action[]=sale',
                true,
                CharsetDetectorInterface::GSM_CHARSET,
            ],
            'Plain ASCII symbols without extension set' => [
                'Black Friday in our web-shop! Sales 20% on everything! Visit shop.tld/?action[]=sale',
                false,
                CharsetDetectorInterface::UCS_CHARSET,
            ],
            'Not ASCII, but gms set allows this symbols' => [
                'Δ¡ òR !Δ¡¿? ΓØØD. 30£ (SìORñ) 75$. It\'s üp 2 "U"! 3 * 3 = 9 < 11;',
                true,
                CharsetDetectorInterface::GSM_CHARSET,
            ],
            'Not ASCII, but gms set allows this symbols even without extension set' => [
                'Δ¡ òR !Δ¡¿? ΓØØD. 30£ (SìORñ) 75$. It\'s üp 2 "U"! 3 * 3 = 9 < 11;',
                false,
                CharsetDetectorInterface::GSM_CHARSET,
            ],
            'Cyrillic symbols are utf, always-1' => [
                'ЧернаяПятница в нашем магазине! Покупай со скидкой до 30%!',
                true,
                CharsetDetectorInterface::UCS_CHARSET,
            ],
            'Cyrillic symbols are utf, always-2' => [
                'ЧернаяПятница в нашем магазине! Покупай со скидкой до 30%!',
                false,
                CharsetDetectorInterface::UCS_CHARSET,
            ],
            'Non gsm symbols (á, ó, ú) are utf-1' => [
                'BlackFridáy! 20% dtó for ú',
                true,
                CharsetDetectorInterface::UCS_CHARSET,
            ],
            'Non gsm symbols (á, ó, ú) are utf-2' => [
                'BlackFridáy! 20% dtó for ú',
                false,
                CharsetDetectorInterface::UCS_CHARSET,
            ],
            'Sending emoji with extension set' => [
                'Hello! Do you 😜? Do you 😃? Noooooo 😩!',
                true,
                CharsetDetectorInterface::UCS_CHARSET,
            ],
            'Sending emoji without extension set' => [
                'Hello! Do you 😜? Do you 😃? Noooooo 😩!',
                false,
                CharsetDetectorInterface::UCS_CHARSET,
            ],
            'Line break in the text-1' => [
                'Hello!' . "\n" . 'How are you?',
                true,
                CharsetDetectorInterface::GSM_CHARSET,
            ],
            'Line break in the text-2' => [
                'Hello!' . "\n" . 'How are you?',
                false,
                CharsetDetectorInterface::GSM_CHARSET,
            ],
            'Some spanish text with extension set' => [
                '¡Black Friday! Desde ayer 20% dto. en TODAS LAS MARCAS. Descuento disponible en tiendas y online. ¡No te quedes sin tu talla! bit.ly/2VhzgON',
                true,
                CharsetDetectorInterface::GSM_CHARSET,
            ],
            'Some spanish text without extension set' => [
                '¡Black Friday! Desde ayer 20% dto. en TODAS LAS MARCAS. Descuento disponible en tiendas y online. ¡No te quedes sin tu talla! bit.ly/2VhzgON',
                false,
                CharsetDetectorInterface::GSM_CHARSET,
            ],
            'Just one symbol makes your sms utf, extenion set included' => [
                '¡Black Friday! Desde ayer 20% dto. en TODAS LAS MARCAS. Descuento {disponible} en tiendas и online. ¡No te quedes sin tu talla! bit.ly/2VhzgON',
                true,
                CharsetDetectorInterface::UCS_CHARSET,
            ],
            'Just one symbol makes your sms utf, extenion set excluded' => [
                '¡Black Friday! Desde ayer 20% dto. en TODAS LAS MARCAS. Descuento {disponible} en tiendas. ¡No te quedes sin tu talla! bit.ly/2VhzgON',
                false,
                CharsetDetectorInterface::UCS_CHARSET,
            ],
        ];
    }
}
