<?php

require __DIR__.'/silex.phar';

$app = new Silex\Application();

$app->register(new Silex\Extension\MonologExtension(), array(
	'monolog.class_path' => __DIR__.'/vendor/monolog/src',
	'monolog.logfile' => __DIR__.'/application.log',
));

$app->get('/hello', function() use ($app) {
	$app['monolog']->addDebug('currently at hello');
	return 'Hello World!';
});

$app->get('/error', function() {
	throw new RuntimeException('Some error');
});

$app->run();
