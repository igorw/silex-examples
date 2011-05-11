<?php

require_once __DIR__.'/silex.phar';

use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

$app->register(new Silex\Extension\MonologExtension(), array(
    'monolog.logfile'       => __DIR__.'/development.log',
    'monolog.class_path'    => __DIR__.'/vendor/monolog/src',
));

$app['autoloader']->registerNamespace('Zend', __DIR__.'/vendor/zf2/library');

class SoapService
{
    /**
     * Returns a hello world
     *
     * @param string $name
     * @return string the hello world
     */
    public function hello($name)
    {
        return "Hello $name!";
    }
}

$app->before(function () use ($app) {
    $app['base_url'] = $app['request']->getScheme().'://'.$app['request']->getHttpHost().$app['request']->getBaseUrl();

    ini_set("soap.wsdl_cache_enabled", "0");
});

$app->match('/', function () use ($app) {
    $server = new Zend\Soap\Server($app['base_url'].'/wsdl');
    $server->setObject(new SoapService());
    $server->setReturnResponse(true);
    $response = $server->handle($app['request']->getContent());
    return $response;
});

$app->get('/wsdl', function () use ($app) {
    $autodiscover = new Zend\Soap\AutoDiscover();
    $autodiscover->setClass('SoapService');
    $autodiscover->setUri($app['base_url']);
    $wsdl = $autodiscover->toXml();

    return new Response($wsdl, 200, array('Content-Type' => 'application/xml'));
});

$app->get('/client', function () use ($app) {
    $client = new Zend\Soap\Client($app['base_url'].'/wsdl');
    return $client->hello("igor");
});

$app->run();
