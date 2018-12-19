<?php

namespace App\Symfony\Command;

use App\Doctrine\Entity\ModifiedKeyword;
use App\Doctrine\Repository\ModifiedKeywordRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddKeywordModifier extends BaseCommand
{
    /**
     * @var ModifiedKeywordRepository $modifiedKeywordRepository
     */
    private $modifiedKeywordRepository;
    /**
     * AddKeywordModifier constructor.
     * @param ModifiedKeywordRepository $modifiedKeywordRepository
     */
    public function __construct(
        ModifiedKeywordRepository $modifiedKeywordRepository
    ) {
        $this->modifiedKeywordRepository = $modifiedKeywordRepository;

        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:add_keyword_modifier');

        $this->addArgument('keyword', InputArgument::REQUIRED, 'Keyword to modify');

        $this->addArgument(
            'excluded',
            InputArgument::REQUIRED,
            'Excluded keywords (separate multiple names with a space)?'
        );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->makeEasier($input, $output);

        $this->validate();

        $keyword = $this->input->getArgument('keyword');
        $excluded = $this->input->getArgument('excluded');

        $excludedJson = jsonEncodeWithFix(explode(',', $excluded));

        $this->modifiedKeywordRepository->persistAndFlush($this->createModifiedKeyword(
            strToLowerWithEncoding(trim($keyword)),
            $excludedJson
        ));
    }
    /**
     * @throws \RuntimeException
     */
    private function validate()
    {
        if (!is_string($this->input->getArgument('keyword')) OR
            !is_string($this->input->getArgument('excluded'))) {

            $message = sprintf(
                'Arguments \'keyword\' or \'excluded\' not specified'
            );

            throw new \RuntimeException($message);
        }
    }
    /**
     * @param string $keyword
     * @param string $excluded
     * @return ModifiedKeyword
     */
    private function createModifiedKeyword(
        string $keyword,
        string $excluded
    ): ModifiedKeyword {
        return new ModifiedKeyword($keyword, $excluded);
    }
}