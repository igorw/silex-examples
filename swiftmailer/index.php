<?php

// require __DIR__.'/silex.phar';
require '/Users/igor/code/silex/autoload.php';

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Extension\SwiftmailerExtension(), array(
    'swiftmailer.class_path'	=> __DIR__.'/vendor/swiftmailer/lib',
));

$app->post('/feedback', function () use ($app) {
    $request = $app['request'];

    $message = \Swift_Message::newInstance()
        ->setSubject('[YourSite] Feedback')
        ->setFrom(array('noreply@yoursite.com'))
        ->setTo(array('igor@wiedler.ch'))
        ->setBody($request->get('message'));

    $app['mailer']->send($message);

    return new Response('Thank you for your feedback!', 201);
});

$app->run();
