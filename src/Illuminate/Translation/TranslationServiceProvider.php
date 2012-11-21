<?php namespace Illuminate\Translation;

use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @param  Illuminate\Foundation\Application  $app
	 * @return void
	 */
	public function register($app)
	{
		$this->registerLoader($app);

		$app['translator'] = $app->share(function($app)
		{
			$loader = $app['translation.loader'];

			// When registering the translator component, we'll need to set the default
			// locale as well as the fallback locale. So, we'll grab the application
			// configuration so we can easily get both of these values from there.
			$locale = $app['config']['app.locale'];

			$fallback = $app['config']['app.fallback_locale'];

			$trans = new Translator($loader, $locale, $fallback);

			return $trans;
		});
	}

	/**
	 * Register the translation line loader.
	 *
	 * @param  Illuminate\Foundation\Application  $app
	 * @return void
	 */
	protected function registerLoader($app)
	{
		$app['translation.loader'] = $app->share(function($app)
		{
			return new FileLoader($app['files'], $app['config']['app.locale_path']);
		});
	}

}