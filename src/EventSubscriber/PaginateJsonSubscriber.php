<?php

namespace App\EventSubscriber;

use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class PaginateJsonSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['normalize', EventPriorities::PRE_RESPOND],
        ];
    }

    public function normalize(ViewEvent $event): void
    {
        $method = $event->getRequest()->getMethod();
        $attributes = $event->getRequest()->attributes;
        if (Request::METHOD_GET !== $method) {
            return;
        }
        if (!$attributes->has('_api_resource_class')) {
            return;
        }

        $operation = $attributes->get('_api_operation');
        if (!$operation instanceof CollectionOperationInterface) {
            return;
        }

        if (($data = $attributes->get('data')) && $data instanceof Paginator) {
            $json = json_decode($event->getControllerResult(), true);

            $pagination = [
                'first' => 1,
                'current' => $data->getCurrentPage(),
                'last' => $data->getLastPage(),
                'previous' => $data->getCurrentPage() - 1 <= 0 ? 1 : $data->getCurrentPage() - 1,
                'next' => $data->getCurrentPage() + 1 > $data->getLastPage() ? $data->getLastPage() : $data->getCurrentPage() + 1,
                'totalItems' => $data->getTotalItems(),
                'itemsPerPage' => count($data),
            ];
            $json['hydra:meta'] = [
                'pagination' => $pagination,
            ];

            $event->setControllerResult(json_encode($json));
        }
    }
}
