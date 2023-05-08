<?php

namespace App\EventListener;

use App\Service\RedisService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestListener implements EventSubscriberInterface
{
    public function __construct(private readonly RedisService $redisService)
    {

    }

    /**
     * @param  RequestEvent $event
     * @return void
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        // Set the query string params
        $requestContent = $event->getRequest()->getQueryString();
        if (!empty($requestContent)) {
            // Hash the request content and fetch it from cache if data are exist
            $requestHash = md5($requestContent);
            $content = $this->redisService->get($requestHash);
            // Read data from cache and respond it to client
            if (!empty($content)) {
                $content = json_decode($content);
                $response = new JsonResponse($content);
                $event->setResponse($response);
                return;
            }
        }
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
