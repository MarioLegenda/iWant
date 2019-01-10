<?php

namespace App\Symfony\Command\Deploy;

use Mage\Task\BuiltIn\Composer\AbstractComposerTask;
use Symfony\Component\Process\Process;

class ComposerDumpAutoload extends AbstractComposerTask
{
    public function getName()
    {
        return 'composer/custom-dump-autoload';
    }

    public function getDescription()
    {
        return '[Composer] dump-autoload --optimize --no-dev --classmap-authoritative';
    }

    public function execute()
    {
        $options = $this->getOptions();
        $cmd = sprintf('%s dump-autoload --optimize --no-dev --classmap-authoritative', $options['path']);

        /** @var Process $process */
        $process = $this->runtime->runLocalCommand($cmd, false);

        return $process->isSuccessful();
    }
}