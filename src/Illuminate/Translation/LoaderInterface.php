<?php namespace Illuminate\Translation;

interface LoaderInterface {

	/**
	 * Load the messages for the given locale.
	 *
	 * @param  string  $locale
	 * @return array
	 */
	public function loadLocale($locale);

}