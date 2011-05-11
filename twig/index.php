<?php

require __DIR__.'/silex.phar';

$app = new Silex\Application();

$app->register(new Silex\Extension\TwigExtension(), array(
    'twig.path'         => __DIR__.'/views',
    'twig.class_path'   => __DIR__.'/vendor/twig/lib',
));

$app->before(function () use ($app) {
    $app['twig']->addGlobal('layout', $app['twig']->loadTemplate('layout.twig'));
});

$app->match('/', function () use ($app) {
    return $app['twig']->render('index.twig');
});

$app->run();
