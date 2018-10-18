<?php

namespace App\Symfony\Command\Deploy;

use Mage\Task\AbstractTask;
use Symfony\Component\Process\Process;

class RunWebpackBuild extends AbstractTask
{
    public function getName()
    {
        return 'git/update';
    }

    public function getDescription()
    {
        return 'Webpack is compiling the latest build';
    }

    public function execute()
    {
        $command = sprintf(
            'frontend/application/node_modules/.bin/webpack --mode production --config webpack.config-prod.js'
        );

        /** @var Process $process */
        $process = $this->runtime->runRemoteCommand($command, true);

        return $process->isSuccessful();
    }
}