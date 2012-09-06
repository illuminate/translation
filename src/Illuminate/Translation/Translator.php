<?php namespace Illuminate\Translation;

use Illuminate\Support\NamespacedItemResolver;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Translation\Translator as SymfonyTranslator;

class Translator extends NamespacedItemResolver implements TranslatorInterface {

	/**
	 * The loader implementation.
	 *
	 * @var Illuminate\Translation\LoaderInterface
	 */
	protected $loader;

	/**
	 * The Symfony translator instance.
	 *
	 * @var Symfony\Translation\Translator
	 */
	protected $trans;

	/**
	 * The array of loaded translation groups.
	 *
	 * @var array
	 */
	protected $loaded = array();

	/**
	 * Create a new translator instance.
	 *
	 * @param  Illuminate\Translation\LoaderInterface
	 * @param  array   $locales
	 * @param  string  $default
	 * @param  string  $fallback
	 * @return void
	 */
	public function __construct(LoaderInterface $loader, $default, $fallback)
	{
		$this->loader = $loader;

		$this->trans = $this->createSymfonyTranslator($default, $fallback);
	}

	/**
	 * Create a new Symfony translator instance.
	 *
	 * @param  string  $default
	 * @param  string  $fallback
	 * @return Symfony\Component\Translation\Translator
	 */
	protected function createSymfonyTranslator($default, $fallback)
	{
		$trans = new SymfonyTranslator($default);

		// After creating the translator instance we will set the fallback locale
		// as well as the array loader so that messages can be properly loaded
		// from the application. Then we're ready to get the language lines.
		$trans->setFallbackLocale($fallback);

		$trans->addLoader('array', new ArrayLoader);

		return $trans;
	}

	/**
	 * Determine if a translation exists.
	 *
	 * @param  string  $key
	 * @param  string  $locale
	 * @return bool
	 */
	public function has($key, $locale = null)
	{
		return $this->get($key, array(), $locale) !== $key;
	}

	/**
	 * Get the translation for a given key.
	 *
	 * @param  string  $id
	 * @param  array   $parameters
	 * @param  string  $locale
	 * @return string
	 */
	public function get($key, $parameters = array(), $locale = null)
	{
		list($namespace, $group, $item) = $this->parseKey($key);

		// Once we call the "load" method, we will receive back the "domain" for the
		// namespace and group. The "domain" is used by the Symfony translator to
		// logically separate related groups of messages, and should be unique.
		$domain = $this->load($group, $namespace, $locale);

		return $this->trans($item, $parameters, $domain, $locale);
	}

	/**
	 * Get a translation according to an integer value.
	 *
	 * @param  string  $id
	 * @param  int     $number
	 * @param  array   $parameters
	 * @param  string  $locale
	 * @return string
	 */
	public function choice($key, $number, $parameters = array(), $locale = null)
	{
		list($namespace, $group, $item) = $this->parseKey($key);

		$domain = $this->load($group, $namespace, $locale);

		return $this->transChoice($item, $number, $parameters, $domain, $locale);
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
	public function trans($id, array $parameters = array(), $domain = 'messages', $locale = null)
	{
		return $this->trans->trans($id, $parameters, $domain, $locale);
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
	public function transChoice($id, $number, array $parameters = array(), $domain = 'messages', $locale = null)
	{
		return $this->trans->transChoice($id, $number, $parameters, $domain, $locale);
	}

	/**
	 * Load the specified language group.
	 *
	 * @param  string  $group
	 * @param  string  $namespace
	 * @param  string  $locale
	 * @return string
	 */
	public function load($group, $namespace, $locale)
	{
		$domain = $namespace.'::'.$group;

		// First we'll make sure that this language group hasn't already been loaded
		// for the given locale and namespace. If it has, we will simply bail out
		// and not do any further processing so each group is loaded only once.
		$locale = $locale ?: $this->getLocale();

		if ($this->loaded($group, $namespace, $locale))
		{
			return $domain;
		}

		$lines = $this->loader->load($locale, $group, $namespace);

		// The domain is used to store the messages in the Symfony translator object
		// and functions as a sort of logical separator of message types so we'll
		// use the namespace and group as the "domain", which should be unique.
		$this->addResource($lines, $locale, $domain);

		$this->setLoaded($group, $namespace, $locale);

		return $domain;
	}

	/**
	 * Add an array resource to the Symfony translator.
	 *
	 * @param  array   $lines
	 * @param  string  $locale
	 * @param  string  $domain
	 * @return void
	 */
	protected function addResource(array $lines, $locale, $domain)
	{
		$this->trans->addResource('array', $lines, $locale, $domain);
	}

	/**
	 * Determine if the given group has been loaded.
	 *
	 * @param  string  $group
	 * @param  string  $namespace
	 * @param  string  $locale
	 * @return bool
	 */
	protected function loaded($group, $namespace, $locale)
	{
		return array_key_exists($group.$namespace.$locale, $this->loaded);
	}

	/**
	 * Set the given translation group as being loaded.
	 *
	 * @param  string  $group
	 * @param  string  $namespace
	 * @param  string  $locale
	 * @return void
	 */
	protected function setLoaded($group, $namespace, $locale)
	{
		$this->loaded[$group.$namespace.$locale] = true;
	}

	/**
	 * Add a new namespace to the loader.
	 *
	 * @param  string  $namespace
	 * @param  string  $hint
	 * @return void
	 */
	public function addNamespace($namespace, $hint)
	{
		$this->loader->addNamespace($namespace, $hint);
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

	/**
	 * Get the base Symfony translator instance.
	 *
	 * @param  Symfony\Translation\Translator  $trans
	 * @return void
	 */
	public function setSymfonyTranslator(SymfonyTranslator $trans)
	{
		$this->trans = $trans;
	}

}