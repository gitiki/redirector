<?php

namespace Gitiki\Redirector;

use Gitiki\ExtensionInterface,
    Gitiki\Gitiki;

class RedirectorExtension implements ExtensionInterface
{
    public function register(Gitiki $gitiki, array $config)
    {
        $gitiki['dispatcher'] = $gitiki->share($gitiki->extend('dispatcher', function ($dispatcher, $gitiki) {
            $dispatcher->addSubscriber(new Event\Listener\RedirectListener($gitiki['path_resolver']));

            return $dispatcher;
        }));
    }

    public function boot(Gitiki $gitiki)
    {
        $gitiki->error(function ($e, $code) use ($gitiki) {
            if ($e instanceof Exception\PageRedirectedException) {
                return $gitiki->redirect($gitiki->path('page', ['path' => $e->getTarget()]), 301);
            }
        });
    }
}
