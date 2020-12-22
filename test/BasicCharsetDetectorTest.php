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
    
    // TODO
    public function provideDetectCharsetMessages(): array
    {
        return [
            ['Test message', true, CharsetDetectorInterface::GSM_CHARSET],
        ];
    }
    
    // TODO
    public function provideIsGsmCharsetMessages(): array
    {
        return [
            ['Test message', true, true],
        ];
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
}
