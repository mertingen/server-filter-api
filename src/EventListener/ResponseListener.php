<?php

namespace App\EventListener;

use App\Service\RedisService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ResponseListener implements EventSubscriberInterface
{
    public function __construct(private readonly RedisService $redisService)
    {

    }

    /**
     * @param  ResponseEvent $event
     * @return void
     */
    public function onKernelResponse(ResponseEvent $event): void
    {
        // Set the query string params
        $requestContent = $event->getRequest()->getQueryString();
        if (!empty($requestContent)) {
            // Save the response content into the cache
            // Next same request will be provided from cache in src/EventListener/RequestListener.php
            $requestHash = md5($requestContent);
            $this->redisService->set($requestHash, $event->getResponse()->getContent(), 120);
        }
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}
