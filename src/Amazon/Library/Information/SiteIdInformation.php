<?php

namespace App\Amazon\Library\Information;

use App\Library\Information\InformationInterface;

class SiteIdInformation implements InformationInterface
{
    const AU = 'AU';
    const BR = 'BR';
    const CA = 'CA';
    const CN = 'CN';
    const DE = 'DE';
    const ES = 'ES';
    const FR = 'FR';
    const IN = 'IN';
    const IT = 'IT';
    const JP = 'JP';
    const MX = 'MX';
    const UK = 'UK';
    const US = 'US';
    /**
     * @var array $siteIds
     */
    private $siteIds = [
        'au' => '.com.au',
        'br' => '.com.br',
        'ca' => '.ca',
        'cn' => '.cn',
        'de' => '.de',
        'es' => '.es',
        'fr' => '.fr',
        'in' => '.in',
        'it' => '.it',
        'jp' => '.co.jp',
        'mx' => '.com.mx',
        'uk' => '.co.uk',
        'us' => '.com',
    ];
    /**
     * @var SiteIdInformation $instance
     */
    private static $instance;
    /**
     * @return SiteIdInformation
     */
    public static function instance()
    {
        static::$instance = (static::$instance instanceof static) ? static::$instance : new static();

        return static::$instance;
    }
    /**
     * @param string $siteId
     * @return mixed
     */
    public function has(string $siteId) : bool
    {
        return array_key_exists(strtolower($siteId), $this->siteIds);
    }
    /**
     * @param string $siteId
     * @return string
     */
    public function get(string $siteId): string
    {
        if (!$this->has($siteId)) {
            return null;
        }

        return $this->siteIds[strtolower($siteId)];
    }
    /**
     * @param $siteId
     * @return InformationInterface
     */
    public function add(string $siteId) : InformationInterface
    {
        if ($this->has($siteId)) {
            return null;
        }

        $this->siteIds[] = $siteId;

        return self::$instance;
    }
    /**
     * @param string $entry
     * @return bool
     */
    public function remove(string $entry): bool
    {
        $position = array_search($entry, $this->siteIds);

        if (array_key_exists($position, $this->siteIds)) {
            unset($this->siteIds[$position]);

            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getAll() : array
    {
        return $this->siteIds;
    }
}