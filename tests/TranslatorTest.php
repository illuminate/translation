<?php

use Illuminate\Translation\Translator;

class TranslatorTest extends PHPUnit_Framework_TestCase {

	public function testTranslatorCanBeCreated()
	{
		$t = new Translator(array('en'), 'en', 'fr');
		$this->assertInstanceOf('Illuminate\Translation\Translator', $t);
	}

}