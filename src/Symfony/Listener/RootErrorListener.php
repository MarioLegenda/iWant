<?php

namespace App\Symfony\Listener;

use App\Ebay\Library\Exception\BaseEbayException;
use App\Library\Exception\ExceptionCode;
use App\Library\Exception\HttpTransferException;
use App\Library\Exception\UnhandledSystemException;
use App\Library\Exception\UnhandledSystemExceptionInformation;
use App\Yandex\Library\Exception\YandexBaseException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class RootErrorListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof BaseEbayException) {
            $event->setException($exception);

            return null;
        }

        if ($exception instanceof HttpTransferException) {
            $event->setException($exception);

            return null;
        }

        if ($exception instanceof YandexBaseException) {
            $event->setException($exception);

            return null;
        }

        if ($exception instanceof \Exception) {
            $unhandledSystemException = new UnhandledSystemExceptionInformation(
                ExceptionCode::UNHANDLED_SYSTEM_EXCEPTION,
                $exception->getMessage()
            );

            $exception = new UnhandledSystemException($unhandledSystemException);

            $event->setException($exception);

            return null;
        }

        if ($exception instanceof \Error) {
            $unhandledSystemException = new UnhandledSystemExceptionInformation(
                ExceptionCode::UNHANDLED_SYSTEM_EXCEPTION,
                $exception->getMessage()
            );

            $exception = new UnhandledSystemException($unhandledSystemException);

            $event->setException($exception);

            return null;
        }

        if ($exception instanceof \Throwable) {
            $unhandledSystemException = new UnhandledSystemExceptionInformation(
                ExceptionCode::UNHANDLED_SYSTEM_EXCEPTION,
                $exception->getMessage()
            );

            $exception = new UnhandledSystemException($unhandledSystemException);

            $event->setException($exception);

            return null;
        }
    }
}