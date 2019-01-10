<?php

namespace App\Symfony\Command\Deploy;

use Mage\Task\AbstractTask;
use Symfony\Component\Process\Process;

class OpCacheReset extends AbstractTask
{
    public function getName()
    {
        return 'deploy/opcache-reset';
    }

    public function getDescription()
    {
        return '[Post-release] OpCache reset';
    }

    public function execute()
    {
        $hostPath = rtrim($this->runtime->getEnvOption('host_path'), '/');
        $currentReleaseId = $this->runtime->getReleaseId();
        $maxReleases = $this->runtime->getEnvOption('releases');

        $cmdListReleases = sprintf('php -r "opcache_reset();"', $hostPath);

        /** @var Process $process */
        $process = $this->runtime->runRemoteCommand($cmdListReleases, false);

        return $process->isSuccessful();
    }
}