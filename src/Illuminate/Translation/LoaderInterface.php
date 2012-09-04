<?php namespace Illuminate\Translation;

interface LoaderInterface {

	/**
	 * Load the messages for the given locale.
	 *
	 * @param  string  $locale
	 * @return array
	 */
	public function loadLocale($locale);

	/**
	 * Load the messages for a hinted locale.
	 *
	 * @param  string  $locale
	 * @param  string  $hint
	 * @return array
	 */
	public function loadLocaleFromHint($locale, $hint);

}