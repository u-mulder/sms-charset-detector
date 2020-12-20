<?php

declare(strict_types=1);

class BasicCharsetDetectorTest extends 
{
    /**
     * @dataProvider provideMessages
     */
    public function testDetectCharset(string $message, bool $includeExtensionSet, string $expectedCharset): void
    {
		$detector = $this->getCharsetDetector($includeExtensionSet);
		
		$this->assertSame(
			$isGsmCharset,
			$detector->detectCharset($message)
		);
    }

    /**
     * @dataProvider 
     */
    public function testIsGsmCharset(string $message, bool $includeExtensionSet, bool $isGsmCharset): void
    {
		$detector = $this->getCharsetDetector($includeExtensionSet);

		$this->assertSame(
			$isGsmCharset,
			$detector->isGsmCharset($message)
		);
    }
    
    
    public function testGetGsmCharsetAsJson(): void
    {
        $detector = $this->getCharsetDetector(true);

		$this->assertString($detector->getGsmCharsetAsJson());

        $detector->setIncludeExtensionSet(false);
        
		$this->assertString($detector->getGsmCharsetAsJson());
    }
	
    // TODO
	public function provideMessages(): \Generator	// array
	{
		$messages = [];
		return [
			[],
			[],
			[],
			[],
			[],
			[],
			[],
			[],
			[],
		];
	}
    
    public function provideMessages(): array
    {
        return [


        ];
    }

    
    protected function getCharsetDetector(bool $includeExtensionSet): BasicCharsetDetector
    {
        return new Um\BasicCharsetDetector($includeExtensionSet);
    }
}
