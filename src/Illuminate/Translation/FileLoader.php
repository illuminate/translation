<?php namespace Illuminate\Translation;

use Illuminate\Filesystem;

class FileLoader implements LoaderInterface {

	/**
	 * The filesystem instance.
	 *
	 * @var Illuminate\Filesystem
	 */
	protected $files;

	/**
	 * The default path for the loader.
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * Create a new file loader instance.
	 *
	 * @param  Illuminate\Filesystem  $files
	 * @return void
	 */
	public function __construct(Filesystem $files, $path)
	{
		$this->path = $path;
		$this->files = $files;
	}

	/**
	 * Load the messages for the given locale.
	 *
	 * @param  string  $locale
	 * @return array
	 */
	public function loadLocale($locale)
	{
		return $this->loadLocaleFromPath($this->path, $locale);
	}

	/**
	 * Load the messages for a hinted locale.
	 *
	 * @param  string  $locale
	 * @param  string  $hint
	 * @return array
	 */
	public function loadLocaleFromHint($locale, $hint)
	{
		return $this->loadLocaleFromPath($hint, $locale);
	}

	/**
	 * Load a locale from a given path.
	 *
	 * @param  string  $path
	 * @param  string  $locale
	 * @return array
	 */
	public function loadLocaleFromPath($path, $locale)
	{
		if ($this->files->exists($full = $path.'/'.$locale.'.php'))
		{
			return $this->files->getRequire($full);
		}

		return array();
	}

}