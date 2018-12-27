<?php

namespace App\Cache\Cache;

use App\Doctrine\Entity\TextLocaleIdentifier;
use App\Doctrine\Repository\TextLocaleIdentifierRepository;

class TextLocaleIdentifierCache
{
    /**
     * @var TextLocaleIdentifierRepository $textLocaleIdentifierRepository
     */
    private $textLocaleIdentifierRepository;
    /**
     * TextLocaleIdentifierCache constructor.
     * @param TextLocaleIdentifierRepository $textLocaleIdentifierRepository
     */
    public function __construct(
        TextLocaleIdentifierRepository $textLocaleIdentifierRepository
    ) {
        $this->textLocaleIdentifierRepository = $textLocaleIdentifierRepository;
    }
    /**
     * @param string $text
     * @param string $locale
     * @return TextLocaleIdentifier|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function get(
        string $text,
        string $locale
    ): ?TextLocaleIdentifier {
        /** @var TextLocaleIdentifier $textLocaleIdentifier */
        $textLocaleIdentifier = $this->textLocaleIdentifierRepository->findOneBy([
            'text' => $text,
            'locale' => $locale,
        ]);

        return ($textLocaleIdentifier instanceof TextLocaleIdentifier) ?
            $textLocaleIdentifier :
            null;
    }
    /**
     * @param string $text
     * @param string $locale
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function set(
        string $text,
        string $locale
    ): void {
        $cache = $this->createTextLocaleIdentifierEntity(
            $text,
            $locale
        );

        $this->textLocaleIdentifierRepository->persistAndFlush($cache);
    }
    /**
     * @param string $text
     * @param $locale
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(string $text, $locale): bool
    {
        /** @var TextLocaleIdentifier $textLocaleIdentifier */
        $textLocaleIdentifier = $this->textLocaleIdentifierRepository->findOneBy([
            'text' => $text,
            'locale' => $locale,
        ]);

        if ($textLocaleIdentifier instanceof TextLocaleIdentifier) {
            $this->textLocaleIdentifierRepository->getManager()->remove($textLocaleIdentifier);
            $this->textLocaleIdentifierRepository->getManager()->flush();

            return true;
        }

        return false;
    }
    /**
     * @param string $text
     * @param string $locale
     * @return TextLocaleIdentifier
     */
    private function createTextLocaleIdentifierEntity(
        string $text,
        string $locale
    ): TextLocaleIdentifier {
        return new TextLocaleIdentifier(
            $text,
            $locale
        );
    }
}