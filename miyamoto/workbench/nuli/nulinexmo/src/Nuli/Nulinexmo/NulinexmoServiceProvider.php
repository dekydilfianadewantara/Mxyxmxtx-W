<?php namespace Nuli\Nulinexmo;

use Illuminate\Support\ServiceProvider;

class NulinexmoServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('nuli/nulinexmo');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['nulinexmo'] = $this->app->share(function($app){
			return new Nulinexmo;
		});

		$this->app->booting(function()
		{
		  $loader = \Illuminate\Foundation\AliasLoader::getInstance();
		  $loader->alias('Nulinexmo', 'Nuli\Nulinexmo\Facades\Nulinexmo');
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('nulinexmo');
	}

}
