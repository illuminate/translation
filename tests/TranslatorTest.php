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
		$t = new Translator(m::mock('Illuminate\Translation\LoaderInterface'), array('en'), 'en', 'fr');

		$this->assertInstanceOf('Illuminate\Translation\Translator', $t);
		$this->assertEquals('en', $t->getSymfonyTranslator()->getLocale());
	}


	public function testLoadTranslationsCallsLoaderInterface()
	{
		$t = new Translator($loader = m::mock('Illuminate\Translation\LoaderInterface'), array('en', 'sp'), 'en', 'sp');
		$loader->shouldReceive('loadLocale')->once()->with('en')->andReturn(array('en.messages'));
		$loader->shouldReceive('loadLocale')->once()->with('sp')->andReturn(array('sp.messages'));
		$t->setSymfonyTranslator($base = m::mock('Symfony\Component\Translation\Translator'));
		$base->shouldReceive('addResource')->once()->with('array', array('en.messages'), 'en');
		$base->shouldReceive('addResource')->once()->with('array', array('sp.messages'), 'sp');

		$t->loadTranslations();
	}


	public function testNamedPathsMayBeLoaded()
	{
		$t = new Translator($loader = m::mock('Illuminate\Translation\LoaderInterface'), array('en', 'sp'), 'en', 'sp');
		$loader->shouldReceive('loadLocaleFromHint')->once()->with('en', __DIR__)->andReturn(array('en.messages'));
		$t->setSymfonyTranslator($base = m::mock('Symfony\Component\Translation\Translator'));
		$base->shouldReceive('addResource')->once()->with('array', array('en.messages'), 'en', 'foo');

		$t->addNamedPath('foo', __DIR__, array('en'));
	}	


	public function testMessagesMayBeRetrieved()
	{
		$t = new Translator($loader = m::mock('Illuminate\Translation\LoaderInterface'), array('en', 'sp'), 'en', 'sp');
		$t->setSymfonyTranslator($base = m::mock('Symfony\Component\Translation\Translator'));
		$base->shouldReceive('trans')->once()->with('baz.bar', array(), 'foo', null)->andReturn('line');

		$this->assertEquals('line', $t->get('foo::baz.bar'));

		$t = new Translator($loader = m::mock('Illuminate\Translation\LoaderInterface'), array('en', 'sp'), 'en', 'sp');
		$t->setSymfonyTranslator($base = m::mock('Symfony\Component\Translation\Translator'));
		$base->shouldReceive('trans')->once()->with('foo.bar', array(), 'messages', null)->andReturn('line');

		$this->assertEquals('line', $t->get('foo.bar'));

		$t = new Translator($loader = m::mock('Illuminate\Translation\LoaderInterface'), array('en', 'sp'), 'en', 'sp');
		$t->setSymfonyTranslator($base = m::mock('Symfony\Component\Translation\Translator'));
		$base->shouldReceive('trans')->once()->with('foo.bar', array('name' => 'taylor'), 'messages', 'en')->andReturn('line');

		$this->assertEquals('line', $t->get('foo.bar', array('name' => 'taylor'), 'en'));
	}

}