<?php

namespace App\Library\OfflineMode;

class OfflineModeMetadata
{
    /**
     * @return string
     */
    public static function getOfflineModeDirectory(): string
    {
        $offlineModeDir = __DIR__.'/../../../var/offline_mode';

        if (!is_dir($offlineModeDir)) {
            mkdir($offlineModeDir, 0777, true);
        }

        return realpath(__DIR__.'/../../../var/offline_mode');
    }
}