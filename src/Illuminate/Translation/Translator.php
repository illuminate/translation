<?php namespace Illuminate\Translation;

use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Translation\Translator as SymfonyTranslator;

class Translator implements TranslatorInterface {

	/**
	 * The Symfony translator instance.
	 *
	 * @var Symfony\Translation\Translator
	 */
	protected $trans;

	/**
	 * The locales that should be loaded.
	 *
	 * @var array
	 */
	protected $locales;

	/**
	 * The default locale for translation.
	 *
	 * @var string
	 */
	protected $default;

	/**
	 * The fallback locale for translation.
	 *
	 * @var string
	 */
	protected $fallback;

	/**
	 * Create a new translator instance.
	 *
	 * @param  array   $locales
	 * @param  string  $default
	 * @param  string  $fallback
	 * @return void
	 */
	public function __construct(array $locales, $default, $fallback)
	{
		$this->locales = $locales;
		$this->default = $default;
		$this->fallback = $fallback;
		$this->trans = $this->createSymfonyTranslator();
	}

	/**
	 * Create a new Symfony translator instance.
	 *
	 * @return Symfony\Component\Translation\Translator
	 */
	protected function createSymfonyTranslator()
	{
		$trans = new SymfonyTranslator($this->default);

		// After creating the translator instance we will set the fallback locale
		// as well as the array loader so that messages can be properly loaded
		// from the application. Then we're ready to get the language lines.
		$trans->setFallbackLocale($this->fallback);

		$trans->addLoader('array', new ArrayLoader);

		return $trans;
	}

	/**
	 * Get the translation for a given key.
	 *
	 * @param  string  $id
	 * @param  array   $parameters
	 * @param  string  $domain
	 * @param  string  $locale
	 * @return string
	 */
	public function trans($id, array $parameters = array(), $domain = null, $locale = null)
	{
		//
	}

	/**
	 * Get a translation according to an integer value.
	 *
	 * @param  string  $id
	 * @param  int     $number
	 * @param  array   $parameters
	 * @param  string  $domain
	 * @param  string  $locale
	 * @return string
	 */
	public function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null)
	{
		//
	}

	/**
	 * Get the default locale being used.
	 *
	 * @return string
	 */
	public function getLocale()
	{
		return $this->trans->getLocale();
	}

	/**
	 * Set the default locale.
	 *
	 * @param  string  $locale
	 * @return void
	 */
	public function setLocale($locale)
	{
		$this->trans->setLocale($locale);
	}

	/**
	 * Get the base Symfony translator instance.
	 *
	 * @return Symfony\Translation\Translator
	 */
	public function getSymfonyTranslator()
	{
		return $this->trans;
	}

}