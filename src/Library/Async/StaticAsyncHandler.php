<?php

namespace App\Library\Async;

class StaticAsyncHandler
{
    /**
     * @param string $title
     * @param string $channel
     * @param string $message
     */
    public static function sendSlackMessage(
        string $title,
        string $channel,
        string $message
    ) {
        $phpDest = 'sudo /usr/bin/php';
        $consoleDest = '/var/www/iwouldlike/bin/console';
        $actualCommand = 'app:send_slack_message';
        $backgroundComand = ' > /dev/null &';
        $command = sprintf(
            '%s %s %s "%s" "%s" "%s" %s',
            $phpDest,
            $consoleDest,
            $actualCommand,
            $title,
            $channel,
            $message,
            $backgroundComand
        );

        exec($command);
    }
}