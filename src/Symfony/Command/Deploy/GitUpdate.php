<?php

namespace App\Symfony\Command\Deploy;

use Mage\Task\AbstractTask;
use Symfony\Component\Process\Process;

class GitUpdate extends AbstractTask
{
    public function getName()
    {
        return 'git/update';
    }

    public function getDescription()
    {
        return '[Git] Update';
    }

    public function execute()
    {
        $options = $this->getOptions();
        $command = sprintf(
            '%s pull origin %s',
            $options['path'],
            $options['branch']
        );

        /** @var Process $process */
        $process = $this->runtime->runLocalCommand($command);

        return $process->isSuccessful();
    }

    protected function getOptions()
    {
        $branch = $this->runtime->getEnvOption('branch', 'master');
        $options = array_merge(
            ['path' => 'git', 'branch' => $branch],
            $this->options
        );

        return $options;
    }
}