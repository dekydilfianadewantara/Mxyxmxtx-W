<?php namespace Hanyamoto\Nexmo;

use Illuminate\Support\ServiceProvider;

class NexmoServiceProvider extends ServiceProvider {

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
		$this->package('hanyamoto/nexmo');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['hanyanexmo'] = $this->app->share(function($app){
			return new HanyaNexmo;
		});

		$this->app->booting(function()
		{
		  $loader = \Illuminate\Foundation\AliasLoader::getInstance();
		  $loader->alias('HanyaNexmo', 'Hanyamoto\Nexmo\Facades\HanyaNexmo');
		});

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('hanyanexmo');
	}

}
