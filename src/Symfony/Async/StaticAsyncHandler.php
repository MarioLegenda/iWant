<?php

namespace App\Symfony\Async;

class StaticAsyncHandler
{
    /**
     * @param string $commandName
     * @param string $title
     * @param string $channel
     * @param string $message
     */
    public static function sendSlackMessage(
        string $commandName,
        string $title,
        string $channel,
        string $message
    ): void {
        $phpDest = 'sudo /usr/bin/php';
        $consoleDest = '/var/www/iwouldlike/bin/console';
        $backgroundComand = ' > /dev/null &';
        $command = sprintf(
            '%s %s %s "%s" "%s" "%s" %s',
            $phpDest,
            $consoleDest,
            $commandName,
            $title,
            $channel,
            htmlentities($message),
            $backgroundComand
        );

        exec($command);
    }

    public static function upsertExternalServiceReport(
        string $commandName,
        AsyncJsonMessageInterface $asyncJsonMessage
    ): void {

    }
}