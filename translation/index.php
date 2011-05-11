<?php

require __DIR__.'/silex.phar';

$app = new Silex\Application();

$app->register(new Silex\Extension\TranslationExtension(), array(
    'translation.class_path' => __DIR__.'/vendor',
));

$app['translator.messages'] = array(
    'en'    => array(
        'hello' => 'Hello %s',
    ),
    'de'    => array(
        'hello' => 'Hallo %s',
    ),
    'fr'    => array(
        'hello' => 'Salut %s',
    ),
);

$app->before(function () use ($app) {
    if ($locale = $app['request']->get('locale')) {
        $app['locale'] = $locale;
    }
});

$app->match('/{locale}/hello/{name}', function ($name) use ($app) {
    return sprintf($app['translator']->trans('hello'), $name);
});

$app->run();
