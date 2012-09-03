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
	 * The translation file storage path.
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * Create a new file loader instance.
	 *
	 * @param  Illuminate\Filesystem  $files
	 * @param  string  $path
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
		if ($this->files->exists($full = $this->path.'/'.$locale.'.php'))
		{
			return $this->files->getRequire($full);
		}

		return array();
	}

}