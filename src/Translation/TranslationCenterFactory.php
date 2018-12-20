<?php

namespace App\Translation;

use App\Doctrine\Entity\TranslationCenterSwitch;
use App\Doctrine\Repository\TranslationCenterSwitchRepository;
use App\Library\Util\Environment;
use App\Symfony\Async\StaticAsyncHandler;

class TranslationCenterFactory
{
    /**
     * @var GoogleCacheableTranslationCenter $googleCacheableTranslationCenter
     */
    private $googleCacheableTranslationCenter;
    /**
     * @var YandexCacheableTranslationCenter $yandexCacheableTranslationCenter
     */
    private $yandexCacheableTranslationCenter;
    /**
     * @var Environment $environment
     */
    private $environment;
    /**
     * @var TranslationCenterSwitchRepository $translationCenterSwitchRepository
     */
    private $translationCenterSwitchRepository;
    /**
     * TranslationCenterFactory constructor.
     * @param GoogleCacheableTranslationCenter $googleCacheableTranslationCenter
     * @param YandexCacheableTranslationCenter $yandexCacheableTranslationCenter
     * @param TranslationCenterSwitchRepository $translationCenterSwitchRepository
     * @param Environment $environment
     */
    public function __construct(
        GoogleCacheableTranslationCenter $googleCacheableTranslationCenter,
        YandexCacheableTranslationCenter $yandexCacheableTranslationCenter,
        TranslationCenterSwitchRepository $translationCenterSwitchRepository,
        Environment $environment
    ) {
        $this->googleCacheableTranslationCenter = $googleCacheableTranslationCenter;
        $this->yandexCacheableTranslationCenter = $yandexCacheableTranslationCenter;
        $this->environment = $environment;
        $this->translationCenterSwitchRepository = $translationCenterSwitchRepository;
    }
    /**
     * @return TranslationCenterInterface
     */
    public function createTranslationCenter(): TranslationCenterInterface
    {
        if ((string) $this->environment === 'dev' OR (string) $this->environment === 'text') {
            return new TranslationCenter($this->yandexCacheableTranslationCenter);
        }

        if ((string) $this->environment === 'prod') {
            /** @var TranslationCenterSwitch $enabledCenter */
            $enabledCenter = $this->translationCenterSwitchRepository->findOneBy([
                'enabled' => true,
            ]);

            if (empty($enabledCenter)) {
                StaticAsyncHandler::sendSlackMessage(
                    'app:send_slack_message',
                    'Translation center does not exist',
                    '#translations_api',
                    sprintf(
                        'Translation center has not been selected and NullTranslationCenter has been returned by the %s factory method. Correct this issue',
                        get_class($this)
                    )
                );

                return new NullTranslationCenter();
            }

            $centerName = $enabledCenter->getName();

            if ($centerName === 'google_translation_center') {
                return new TranslationCenter($this->googleCacheableTranslationCenter);
            } else if ($centerName === 'yandex_translation_center') {
                return new TranslationCenter($this->yandexCacheableTranslationCenter);
            }

            return new TranslationCenter($this->yandexCacheableTranslationCenter);
        }
    }
}