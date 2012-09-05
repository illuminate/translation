<?php namespace Illuminate\Translation;

interface LoaderInterface {

	/**
	 * Load the messages for the given locale.
	 *
	 * @param  string  $locale
	 * @return array
	 */
	public function load($locale);

	/**
	 * Load the messages for a hinted locale.
	 *
	 * @param  string  $locale
	 * @param  string  $namespace
	 * @param  string  $hint
	 * @return array
	 */
	public function loadNamespaced($locale, $namespace, $hint);

}