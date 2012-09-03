<?php

use Mockery as m;
use Illuminate\Translation\Translator;

class TranslatorTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}


	public function testTranslatorCanBeCreated()
	{
		$t = new Translator(array('en'), 'en', 'fr');

		$this->assertInstanceOf('Illuminate\Translation\Translator', $t);
		$this->assertEquals('en', $t->getSymfonyTranslator()->getLocale());
	}


	public function testLoadTranslationsCallsLoaderInterface()
	{
		$t = new Translator(array('en', 'sp'), 'en', 'sp');
		$loader = m::mock('Illuminate\Translation\LoaderInterface');
		$loader->shouldReceive('loadLocale')->once()->with('en')->andReturn(array('en.messages'));
		$loader->shouldReceive('loadLocale')->once()->with('sp')->andReturn(array('sp.messages'));
		$t->setSymfonyTranslator($base = m::mock('Symfony\Component\Translation\Translator'));
		$base->shouldReceive('addResource')->once()->with('array', array('en.messages'), 'en');
		$base->shouldReceive('addResource')->once()->with('array', array('sp.messages'), 'sp');

		$t->loadTranslations($loader);
		$messages = $t->getMessages();
		$this->assertEquals(array('en.messages'), $messages['en']);
		$this->assertEquals(array('sp.messages'), $messages['sp']);
	}

}