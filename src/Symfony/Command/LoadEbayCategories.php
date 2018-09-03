<?php

namespace App\Symfony\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadEbayCategories extends BaseCommand
{
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:load_ebay_categories');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->makeEasier($input, $output);


    }
}