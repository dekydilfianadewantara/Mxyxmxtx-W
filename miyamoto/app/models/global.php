<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/
Event::listen('cron.collectJobs', function() {
    Cron::add('example1', '* * * * *', function() {
                    // Do some crazy things unsuccessfully every minute
//					$VM             = new VisitorModel;
//					 $VM->ip         = "123";
//					 $VM->id_article = 1;
//					 $VM->type       =1;
//					 $VM->save();
    	// Helper::getWinner();
    	$RM = RequestModel::where('expire',0)
			->where('close_date','<',date('Y-m-d H:i').':00')
			->where('status','0')
			->get();
			foreach ($RM as $a => $b) {
					$ID_REQUEST[] = $b->id;
			}
		$now = Date('Y-m-d H:i').':00';
		if(!empty($ID_REQUEST)){
			foreach ($ID_REQUEST as $s => $rec) {
						$Bid = BidModel::orderBy('price','asc')
								->orderBy('created_at','asc')
								->where('id_request',$rec)
								->where('status',0)
								->where('sent',1)
								->where('reply',0)
								->where('ignore',0)
								->first();
						if(!empty($Bid)){
							if(($Bid->finish_time < $now) && ($Bid->reply == 0) && $Bid->ignore == 0){
							
								DB::table('bid')->where('id',$Bid->id)->update(array('ignore' => 1));
								$Bid = BidModel::orderBy('price','asc')
										->orderBy('created_at','asc')
										->where('id_request',$rec)
										->where('status',0)
										->where('sent',0)
										->where('reply',0)
										->first();
								if(!empty($Bid)){
									Nexmo::SendWinner($Bid->id_user,$rec);
									Helper::MessageSent($Bid->id);
								}
							}
						}else{
							$Bid = BidModel::orderBy('price','asc')
									->orderBy('created_at','asc')
									->where('id_request',$rec)
									->where('status',0)
									->where('sent',0)
									->where('reply',0)
									->first();
							if(!empty($Bid)){
								Nexmo::SendWinner($Bid->id_user,$rec);
								Helper::MessageSent($Bid->id);			
							}
						}
				Helper::ExpireRequest($rec);
			}
		}
                });

    // Cron::add('example2', '*/2 * * * *', function() {
    //     // Do some crazy things successfully every two minute
    //     return null;
    // });

    // Cron::add('disabled job', '0 * * * *', function() {
    //     // Do some crazy things successfully every hour
    // }, false);
});



require app_path().'/filters.php';
require app_path().'/events.php';
