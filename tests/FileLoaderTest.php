<?php

use Mockery as m;

class FileLoaderTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}


	public function testLocalesAreProperlyLoaded()
	{
		$files = m::mock('Illuminate\Filesystem');
		$loader = new Illuminate\Translation\FileLoader($files, __DIR__);

		$files->shouldReceive('exists')->once()->with(__DIR__.'/en.php')->andReturn(true);
		$files->shouldReceive('getRequire')->once()->with(__DIR__.'/en.php')->andReturn(array('en.messages'));
		$files->shouldReceive('exists')->once()->with(__DIR__.'/sp.php')->andReturn(false);

		$this->assertEquals(array('en.messages'), $loader->loadLocale('en'));
		$this->assertEquals(array(), $loader->loadLocale('sp'));
	}

}