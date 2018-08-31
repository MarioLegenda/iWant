<?php

namespace App\Ebay\Library\Information;

use App\Library\Information\InformationInterface;

class GlobalIdInformation implements InformationInterface
{
    const EBAY_AT = 'EBAY-AT';
    const EBAY_AU = 'EBAY-AU';
    const EBAY_CH = 'EBAY-CH';
    const EBAY_DE = 'EBAY-DI';
    const EBAY_ENCA = 'EBAY-ENCA';
    const EBAY_ES = 'EBAY-ES';
    const EBAY_FR = 'EBAYFR';
    const EBAY_FRBE = 'EBAY-FRBE';
    const EBAY_FRCA = 'EBAY-FRCA';
    const EBAY_GB = 'EBAY-GB';
    const EBAY_HK = 'EBAY-HK';
    const EBAY_IE = 'EBAY-IE';
    const EBAY_IN = 'EBAY-IN';
    const EBAY_IT = 'EBAY-IT';
    const EBAY_MOTOR = 'EBAY-MOTOR';
    const EBAY_MY = 'EBAY-MY';
    const EBAY_NL = 'EBAY-NL';
    const EBAY_NLBE = 'EBAY-NLBE';
    const EBAY_PH = 'EBAY-PH';
    const EBAY_PL = 'EBAY-PL';
    const EBAY_SG = 'EBAY-SG';
    const EBAY_US = 'EBAY-US';
    /**
     * @var array $globalIds
     */
    private $globalIds = array(
        'ebay-at' => array(
            'global-id' => 'EBAY-AT',
            'language' => 'de-AT',
            'teritory' => 'AT',
            'site-name' => 'Ebay Austria',
            'ebay-site-id' => 16,
            'removed' => false,
        ),
        'ebay-au' => array(
            'global-id' => 'EBAY-AU',
            'language' => 'en-AU',
            'teritory' => 'AU',
            'site-name' => 'Ebay Australia',
            'ebay-site-id' => 15,
            'removed' => false,
        ),
        'ebay-ch' => array(
            'global-id' => 'EBAY-CH',
            'language' => 'de-CH',
            'teritory' => 'CH',
            'site-name' => 'eBay Switzerland',
            'ebay-site-id' => 193,
            'removed' => false,
        ),
        'ebay-de' => array(
            'global-id' => 'EBAY-DE',
            'language' => 'de-DE',
            'teritory' => 'DE',
            'site-name' => 'eBay Germany',
            'ebay-site-id' => 77,
            'removed' => false,
        ),
        'ebay-enca' => array(
            'global-id' => 'EBAY-ENCA',
            'language' => 'en-CA',
            'teritory' => 'CA',
            'site-name' => 'eBay Canada (English)',
            'ebay-site-id' => 2,
            'removed' => false,
        ),
        'ebay-es' => array(
            'global-id' => 'EBAY-ES',
            'language' => 'es-ES',
            'teritory' => 'ES',
            'site-name' => 'eBay Spain',
            'ebay-site-id' => 186,
            'removed' => false,
        ),
        'ebay-fr' => array(
            'global-id' => 'EBAY-FR',
            'language' => 'fr-FR',
            'teritory' => 'FR',
            'site-name' => 'eBay France',
            'ebay-site-id' => 71,
            'removed' => false,
        ),
        'ebay-frbe' => array(
            'global-id' => 'EBAY-FRBE',
            'language' => 'fr-BE',
            'teritory' => 'BE',
            'site-name' => 'eBay Belgium (French)',
            'ebay-site-id' => 23,
            'removed' => false,
        ),
        'ebay-frca' => array(
            'global-id' => 'EBAY-FRCA',
            'language' => 'fr-CA',
            'teritory' => 'CA',
            'site-name' => 'eBay Canada (French)',
            'ebay-site-id' => 210,
            'removed' => false,
        ),
        'ebay-gb' => array(
            'global-id' => 'EBAY-GB',
            'language' => 'en-GB',
            'teritory' => 'GB',
            'site-name' => 'eBay UK',
            'ebay-site-id' => 3,
            'removed' => false,
        ),
        'ebay-hk' => array(
            'global-id' => 'EBAY-HK',
            'language' => 'zh-Hant',
            'teritory' => 'HK',
            'site-name' => 'eBay Hong Kong',
            'ebay-site-id' => 201,
            'removed' => false,
        ),
        'ebay-ie' => array(
            'global-id' => 'EBAY-IE',
            'language' => 'en-IE',
            'teritory' => 'IE',
            'site-name' => 'eBay Ireland',
            'ebay-site-id' => 205,
            'removed' => false,
        ),
        'ebay-in' => array(
            'global-id' => 'EBAY-IN',
            'language' => 'en-IN',
            'teritory' => 'IN',
            'site-name' => 'eBay India',
            'ebay-site-id' => 203,
            'removed' => false,
        ),
        'ebay-it' => array(
            'global-id' => 'EBAY-IT',
            'language' => 'it-IT',
            'teritory' => 'IT',
            'site-name' => 'eBay Italy',
            'ebay-site-id' => 101,
            'removed' => false,
        ),
        'ebay-motor' => array(
            'global-id' => 'EBAY-MOTOR',
            'language' => 'en-US',
            'teritory' => 'US',
            'site-name' => 'eBay Motors',
            'ebay-site-id' => 100,
            'removed' => false,
        ),
        'ebay-my' => array(
            'global-id' => 'EBAY-MY',
            'language' => 'en-MY',
            'teritory' => 'MY',
            'site-name' => 'eBay Malaysia',
            'ebay-site-id' => 207,
            'removed' => false,
        ),
        'ebay-nl' => array(
            'global-id' => 'EBAY-NL',
            'language' => 'nl-NL',
            'teritory' => 'NL',
            'site-name' => 'eBay Netherlands',
            'ebay-site-id' => 146,
            'removed' => false,
        ),
        'ebay-nlbe' => array(
            'global-id' => 'EBAY-NLBE',
            'language' => 'nl-BE',
            'teritory' => 'BE',
            'site-name' => 'eBay Belgium (Dutch)',
            'ebay-site-id' => 123,
            'removed' => false,
        ),
        'ebay-ph' => array(
            'global-id' => 'EBAY-PH',
            'language' => 'en-PH',
            'teritory' => 'PH',
            'site-name' => 'eBay Philippines',
            'ebay-site-id' => 212,
            'removed' => false,
        ),
        'ebay-pl' => array(
            'global-id' => 'EBAY-PL',
            'language' => 'pl-PL',
            'teritory' => 'PL',
            'site-name' => 'eBay Poland',
            'ebay-site-id' => 212,
            'removed' => false,
        ),
        'ebay-sg' => array(
            'global-id' => 'EBAY-SG',
            'language' => 'en-SG',
            'teritory' => 'SG',
            'site-name' => 'eBay Singapore',
            'ebay-site-id' => 216,
            'removed' => false,
        ),
        'ebay-us' => array(
            'global-id' => 'EBAY-US',
            'language' => 'en-US',
            'teritory' => 'US',
            'site-name' => 'eBay United States',
            'ebay-site-id' => 0,
            'removed' => false,
        ),
    );
    /**
     * @var GlobalIdInformation $instance
     */
    private static $instance;
    /**
     * @return GlobalIdInformation
     */
    public static function instance()
    {
        self::$instance = (self::$instance instanceof self) ? self::$instance : new self();

        return self::$instance;
    }
    /**
     * @param string $id
     * @return mixed|null
     */
    public function get(string $id)
    {
        if (!$this->has($id)) {
            return null;
        }

        return $this->globalIds[strtolower($id)]['global-id'];
    }
    /**
     * @param string $id
     * @return mixed
     */
    public function has(string $id) : bool
    {
        $lowered = strtolower($id);
        if (!array_key_exists($lowered, $this->globalIds) or $this->isRemoved($lowered)) {
            return false;
        }

        return true;
    }
    /**
     * @param string $name
     * @param array $values
     * @return GlobalIdInformation
     * @throws \RuntimeException
     */
    public function add(string $name, array $values) : GlobalIdInformation
    {
        if (!array_key_exists('global-id', $values) and !empty($values['global-id'])) {
            throw new \RuntimeException('GlobalId values has to be an array with \'global-id\' key and a non empty \'global-id\' value');
        }

        if ($this->has($name)) {
            throw new \RuntimeException('Global id '.$name.' already exists');
        }

        if (!array_key_exists('global-id', $values) and !empty($values['global-id'])) {
            throw new \RuntimeException('Global id '.$name.' value array has to have at least a global-id key and corresponding value');
        }

        $name = strtolower($name);
        $this->globalIds[$name] = $values;
        $this->globalIds[$name]['removed'] = false;

        return $this;
    }
    /**
     * @param string $id
     * @return bool
     */
    public function remove(string $id) : bool
    {
        if (!$this->has($id)) {
            return false;
        }

        $this->globalIds[strtolower($id)]['removed'] = true;

        return true;
    }
    /**
     * @param string $id
     * @return bool
     */
    public function isRemoved(string $id) : bool
    {
        if (!array_key_exists($id, $this->globalIds)) {
            return false;
        }

        return $this->globalIds[strtolower($id)]['removed'];
    }
    /**
     * @return array
     */
    public function getAll() : array
    {
        return $this->globalIds;
    }
}