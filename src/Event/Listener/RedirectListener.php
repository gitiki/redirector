<?php

namespace Gitiki\Redirector\Event\Listener;

use Gitiki\Event\Events,
    Gitiki\PathResolver,
    Gitiki\Redirector\Exception\PageRedirectedException;

use Symfony\Component\EventDispatcher\EventSubscriberInterface,
    Symfony\Component\EventDispatcher\GenericEvent as Event;

class RedirectListener implements EventSubscriberInterface
{
    public function __construct(PathResolver $pathResolver)
    {
        $this->pathResolver = $pathResolver;
    }

    public function onMeta(Event $event)
    {
        $page = $event->getSubject();

        if (null !== $redirect = $page->getMeta('redirect')) {
            throw new PageRedirectedException($page->getName(), $redirect);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::PAGE_META => ['onMeta', 512],
        ];
    }
}
