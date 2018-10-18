<?php

namespace App\Symfony\Command;

use App\Doctrine\Entity\NativeTaxonomy;
use App\Doctrine\Repository\NativeTaxonomyRepository;
use App\Library\Tools\LockedImmutableHashSet;
use App\Library\Tools\LockedMutableHashSet;
use App\Library\Util\Util;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class UpsertNativeTaxonomy extends BaseCommand
{
    /**
     * @var NativeTaxonomyRepository $nativeTaxonomyRepository
     */
    private $nativeTaxonomyRepository;
    /**
     * UpsertNativeTaxonomy constructor.
     * @param NativeTaxonomyRepository $nativeTaxonomyRepository
     */
    public function __construct(
        NativeTaxonomyRepository $nativeTaxonomyRepository
    ) {
        $this->nativeTaxonomyRepository = $nativeTaxonomyRepository;

        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:upsert_native_taxonomy');

        $this
            ->addArgument('upsertType', InputArgument::REQUIRED)
        ;
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->makeEasier($input, $output);

        $this->output->writeln(sprintf(
            '<info>Starting command %s</info>',
            $this->getName()
        ));

        $arguments = $this->resolveArguments();

        $this->upsertTaxonomy($arguments);
    }
    /**
     * @param iterable $arguments
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function upsertTaxonomy(iterable $arguments): void
    {
        $upsertType = $arguments['upsertType'];

        if ($upsertType === 'new') {
            $newAnswers = $this->askNewQuestions();

            $this->createTaxonomy(
                $newAnswers['name'],
                $newAnswers['description']
            );

            return;
        } else if ($upsertType === 'update') {
            $updateAnswers = $this->askUpdateAnswers();

            $this->updateTaxonomy(
                $updateAnswers['id'],
                $updateAnswers['name'],
                $updateAnswers['description']
            );

            return;
        }

        $message = sprintf(
            'Command %s could not determine the upsert type. This should have handled before this exception was thrown and it is probably a bug that should be fixed',
            $this->getName()
        );

        throw new \RuntimeException($message);
    }
    /**
     * @return LockedMutableHashSet
     */
    private function askNewQuestions(): LockedMutableHashSet
    {
        $answers = LockedMutableHashSet::create([
            'name',
            'description',
        ]);

        $questions = [
            'name' => new Question('Name: '),
            'description' => new Question('Description: '),
        ];

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        /**
         * @var string $name
         * @var Question $question
         */
        foreach ($questions as $name => $question) {
            $answer = $helper->ask($this->input, $this->output, $question);

            if (empty($answer) and !is_string($answer)) {
                $message = sprintf(
                    '\'%s\' has to be a non empty string',
                    $name
                );

                throw new \RuntimeException($message);
            }

            $answers[$name] = $answer;
        }

        return $answers;
    }
    /**
     * @return LockedMutableHashSet
     */
    public function askUpdateAnswers(): LockedMutableHashSet
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $answers = LockedMutableHashSet::create([
            'id',
            'name',
            'description',
        ]);

        /** @var NativeTaxonomy[] $nativeTaxonomies */
        $nativeTaxonomies = $this->nativeTaxonomyRepository->findAll();

        $choices = [];
        /** @var NativeTaxonomy $nativeTaxonomy */
        foreach ($nativeTaxonomies as $nativeTaxonomy) {
            $choice = sprintf(
                '%s %s',
                $nativeTaxonomy->getId().'.',
                $nativeTaxonomy->getName()
            );

            $choices[] = $choice;
        }

        $choiceQuestion = new ChoiceQuestion(
            'Choose a taxonomy to update',
            $choices
        );

        $choiceAnswer = $helper->ask($this->input, $this->output, $choiceQuestion);
        $taxonomyId = (int) substr($choiceAnswer, 0, 1);

        $questions = [
            'name' => new Question('New name: '),
            'description' => new Question('New description: '),
        ];
        /**
         * @var string $name
         * @var Question $question
         */
        foreach ($questions as $name => $question) {
            $answer = $helper->ask($this->input, $this->output, $question);

            if (empty($answer) and !is_string($answer)) {
                $message = sprintf(
                    '\'%s\' has to be a non empty string',
                    $name
                );

                throw new \RuntimeException($message);
            }

            $answers[$name] = $answer;
        }

        $answers['id'] = $taxonomyId;

        return $answers;
    }
    /**
     * @param string $name
     * @param string $description
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function createTaxonomy(
        string $name,
        string $description
    ): void {
        /** @var NativeTaxonomy $existingTaxonomy */
        $existingTaxonomy = $this->nativeTaxonomyRepository->findOneBy([
            'name' => $name
        ]);

        if ($existingTaxonomy instanceof NativeTaxonomy) {
            $message = sprintf(
                'Native taxonomy \'%s\' already exists',
                $name
            );

            throw new \RuntimeException($message);
        }

        $nativeTaxonomy = new NativeTaxonomy($name, $description);

        $this->nativeTaxonomyRepository->persistAndFlush($nativeTaxonomy);

        $this->output->writeln(sprintf(
            '<info>Taxonomy %s created. Exiting</info>',
            $name
        ));
    }
    /**
     * @param int $id
     * @param string $name
     * @param string $description
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function updateTaxonomy(
        int $id,
        string $name,
        string $description
    ): void {
        /** @var NativeTaxonomy $existingTaxonomy */
        $existingTaxonomy = $this->nativeTaxonomyRepository->find($id);

        if (!$existingTaxonomy instanceof NativeTaxonomy) {
            $message = sprintf(
                'Your choice with id %d is not found as a native taxonomy',
                $id
            );

            throw new \RuntimeException($message);
        }

        $oldName = $existingTaxonomy->getName();
        $existingTaxonomy->setName($name);
        $existingTaxonomy->setDescription($description);

        $this->nativeTaxonomyRepository->persistAndFlush($existingTaxonomy);

        $this->output->writeln(sprintf(
            '<info>Taxonomy %s updated to %s. Exiting</info>',
            $oldName,
            $description
        ));
    }
    /**
     * @return LockedMutableHashSet
     */
    private function resolveArguments(): LockedMutableHashSet
    {
        $arguments = LockedMutableHashSet::create([
            'upsertType',
        ]);

        $upsertType = $this->input->getArgument('upsertType');

        if (empty($upsertType)) {
            $message = sprintf(
                '\'upsertType\' argument cannot be empty'
            );

            throw new \RuntimeException($message);
        }

        $types = ['new', 'update'];

        if (in_array($upsertType, $types) === false) {
            $message = sprintf(
                'Upsert types can only be %s', implode(', ', $types)
            );

            throw new \RuntimeException($message);
        }

        $arguments['upsertType'] = $upsertType;

        return $arguments;
    }
}