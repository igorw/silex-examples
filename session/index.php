<?php

require __DIR__.'/silex.phar';

$app = new Silex\Application();

$app->register(new Silex\Extension\SessionExtension());

$app->get('/', function () use ($app) {
	return 'your username is '.$app['session']->get('username');
});

$app->get('/set', function () use ($app) {
	$username = $app['request']->get('username');
	$app['session']->set('username', $username);
	return 'session username been set to '.$username;
});

$app->run();
