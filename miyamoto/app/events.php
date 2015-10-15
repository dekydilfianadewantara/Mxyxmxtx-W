<?php
Event::listen('generic.event',function($client_data) {
	// Event::listen('some.insert');
    return BrainSocket::message('generic.event',array('message'=>'A message from a generic event fired in Laravel!'));
});

Event::listen('some.insert',function($client_data) {
					$VM             = new VisitorModel;
					$VM->ip         = "123";
					$VM->id_article = 1;
					$VM->type       =1;
					$VM->save();
    return BrainSocket::success(array('Saved!'));
});

Event::listen('app.success',function($client_data) {
    return BrainSocket::success(array('There was a Laravel App Success Event!'));
});
 
Event::listen('app.error',function($client_data) {
    return BrainSocket::error(array('There was a Laravel App Error!'));
});