<?php

declare(strict_types=1);

namespace App\Exception;

use App\Controller\Helper;
use App\Exception\NotFound\NotFoundBaseException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class ExceptionListener
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $event->allowCustomResponseCode();

        if ($exception instanceof HttpExceptionInterface) {
            $event->setResponse(Helper::JsonResponse($exception->getCode(), $exception->getMessage(), $exception->getStatusCode()));
        } elseif ($exception instanceof NotFoundBaseException) {
            $event->setResponse(Helper::JsonResponse($exception->getCode(), $exception->getMessage(), Response::HTTP_NOT_FOUND));
        } else {
            $event->setResponse(Helper::JsonResponse($exception->getCode(), $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR));
        }

        $additionalInfo = [
            'statusCode' => $event->getResponse()->getStatusCode(),
            'message' => $exception->getPrevious() ? substr($exception->getPrevious()->getMessage(), 0, 128) : 'Error',
        ];

        $this->logger->error($exception->getFile() . ':' . $exception->getLine() . ':::' . $exception->getMessage(), $additionalInfo);
    }
}
