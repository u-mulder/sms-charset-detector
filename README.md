# sms-charset-detector

![GitHub Workflow Status](https://img.shields.io/github/workflow/status/u-mulder/sms_charset_detector/base-test-suite?style=flat-square)

Detecting sms charset using [gsm charset](https://en.wikipedia.org/wiki/GSM_03.38).

sms-charset-detector helps you to detect whether your sms content contains gsm charset symbols only or not.

## Installation

Install package with [Composer](https://getcomposer.org/):

```
> composer require um/sms-charset-detector
```

## Usage

Simple usage:

```php
use Um\CharsetDetector\BasicCharsetDetector;

$detector = new BasicCharsetDetector();
$message = 'Some message to be sent over sms';

$encoding = $detector->detectCharset($message);
echo $encoding;	// outputs either 'gsm' or 'ucs'

// simple check if charset is 'gsm'
$messageHasGsmCharset = $detector->isGsmCharset($message);
var_dump($messageHasGsmCharset);   // outputs either `true` or `false`
```
