<?php

namespace Gitiki\Redirector;

use Silex\Application,
    Silex\ServiceProviderInterface;

class RedirectorServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['dispatcher'] = $app->share($app->extend('dispatcher', function ($dispatcher, $app) {
            $dispatcher->addSubscriber(new Event\Listener\RedirectListener($app['path_resolver']));

            return $dispatcher;
        }));
    }

    public function boot(Application $app)
    {
        $app->error(function ($e, $code) use ($app) {
            if ($e instanceof Exception\PageRedirectedException) {
                return $app->redirect($app->path('page', ['path' => $e->getTarget()]), 301);
            }
        });
    }
}
