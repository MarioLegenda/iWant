<?php

namespace App\Ebay\Library\Information;

use App\Library\Information\InformationInterface;

class GlobalIdInformation implements InformationInterface
{
    const EBAY_AT = 'EBAY-AT';
    const EBAY_AU = 'EBAY-AU';
    const EBAY_CH = 'EBAY-CH';
    const EBAY_DE = 'EBAY-DE';
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
            'global_id' => 'EBAY-AT',
            'language' => 'de-AT',
            'alpha2Code' => 'AT',
            'teritory' => 'AT',
            'site_name' => 'eBay Austria',
            'ebay_site_id' => 16,
            'removed' => false,
        ),
        'ebay-au' => array(
            'global_id' => 'EBAY-AU',
            'alpha2Code' => 'AU',
            'language' => 'en-AU',
            'teritory' => 'AU',
            'site_name' => 'eBay Australia',
            'ebay_site_id' => 15,
            'removed' => false,
        ),
        'ebay-ch' => array(
            'global_id' => 'EBAY-CH',
            'alpha2Code' => 'CH',
            'language' => 'de-CH',
            'teritory' => 'CH',
            'site_name' => 'eBay Switzerland',
            'ebay_site_id' => 193,
            'removed' => false,
        ),
        'ebay-de' => array(
            'global_id' => 'EBAY-DE',
            'alpha2Code' => 'DE',
            'language' => 'de-DE',
            'teritory' => 'DE',
            'site_name' => 'eBay Germany',
            'ebay_site_id' => 77,
            'removed' => false,
        ),
        'ebay-enca' => array(
            'global_id' => 'EBAY-ENCA',
            'alpha2Code' => 'CA',
            'language' => 'en-CA',
            'teritory' => 'CA',
            'site_name' => 'eBay Canada (English)',
            'ebay_site_id' => 2,
            'removed' => false,
        ),
        'ebay-es' => array(
            'global_id' => 'EBAY-ES',
            'language' => 'es-ES',
            'alpha2Code' => 'ES',
            'teritory' => 'ES',
            'site_name' => 'eBay Spain',
            'ebay_site_id' => 186,
            'removed' => false,
        ),
        'ebay-fr' => array(
            'global_id' => 'EBAY-FR',
            'alpha2Code' => 'FR',
            'language' => 'fr-FR',
            'teritory' => 'FR',
            'site_name' => 'eBay France',
            'ebay_site_id' => 71,
            'removed' => false,
        ),
        'ebay-frbe' => array(
            'global_id' => 'EBAY-FRBE',
            'alpha2Code' => 'BE',
            'language' => 'fr-BE',
            'teritory' => 'BE',
            'site_name' => 'eBay Belgium (French)',
            'ebay_site_id' => 23,
            'removed' => false,
        ),
        'ebay-frca' => array(
            'global_id' => 'EBAY-FRCA',
            'alpha2Code' => 'CA',
            'language' => 'fr-CA',
            'teritory' => 'CA',
            'site_name' => 'eBay Canada (French)',
            'ebay_site_id' => 210,
            'removed' => false,
        ),
        'ebay-gb' => array(
            'global_id' => 'EBAY-GB',
            'alpha2Code' => 'GB',
            'language' => 'en-GB',
            'teritory' => 'GB',
            'site_name' => 'eBay UK',
            'ebay_site_id' => 3,
            'removed' => false,
        ),
        'ebay-hk' => array(
            'global_id' => 'EBAY-HK',
            'alpha2Code' => 'HK',
            'language' => 'zh-Hant',
            'teritory' => 'HK',
            'site_name' => 'eBay Hong Kong',
            'ebay_site_id' => 201,
            'removed' => false,
        ),
        'ebay-ie' => array(
            'global_id' => 'EBAY-IE',
            'alpha2Code' => 'IE',
            'language' => 'en-IE',
            'teritory' => 'IE',
            'site_name' => 'eBay Ireland',
            'ebay_site_id' => 205,
            'removed' => false,
        ),
        'ebay-in' => array(
            'global_id' => 'EBAY-IN',
            'language' => 'en-IN',
            'alpha2Code' => 'IN',
            'teritory' => 'IN',
            'site_name' => 'eBay India',
            'ebay_site_id' => 203,
            'removed' => false,
        ),
        'ebay-it' => array(
            'global_id' => 'EBAY-IT',
            'language' => 'it-IT',
            'alpha2Code' => 'IT',
            'teritory' => 'IT',
            'site_name' => 'eBay Italy',
            'ebay_site_id' => 101,
            'removed' => false,
        ),
        'ebay-motor' => array(
            'global_id' => 'EBAY-MOTOR',
            'alpha2Code' => 'US',
            'language' => 'en-US',
            'teritory' => 'US',
            'site_name' => 'eBay Motors',
            'ebay_site_id' => 100,
            'removed' => false,
        ),
        'ebay-my' => array(
            'global_id' => 'EBAY-MY',
            'language' => 'en-MY',
            'alpha2Code' => 'MY',
            'teritory' => 'MY',
            'site_name' => 'eBay Malaysia',
            'ebay_site_id' => 207,
            'removed' => false,
        ),
        'ebay-nl' => array(
            'global_id' => 'EBAY-NL',
            'language' => 'nl-NL',
            'alpha2Code' => 'NL',
            'teritory' => 'NL',
            'site_name' => 'eBay Netherlands',
            'ebay_site_id' => 146,
            'removed' => false,
        ),
        'ebay-nlbe' => array(
            'global_id' => 'EBAY-NLBE',
            'language' => 'nl-BE',
            'alpha2Code' => 'BE',
            'teritory' => 'BE',
            'site_name' => 'eBay Belgium (Dutch)',
            'ebay_site_id' => 123,
            'removed' => false,
        ),
        'ebay-ph' => array(
            'global_id' => 'EBAY-PH',
            'language' => 'en-PH',
            'alpha2Code' => 'PH',
            'teritory' => 'PH',
            'site_name' => 'eBay Philippines',
            'ebay_site_id' => 212,
            'removed' => false,
        ),
        'ebay-pl' => array(
            'global_id' => 'EBAY-PL',
            'alpha2Code' => 'PL',
            'language' => 'pl-PL',
            'teritory' => 'PL',
            'site_name' => 'eBay Poland',
            'ebay_site_id' => 212,
            'removed' => false,
        ),
        'ebay-sg' => array(
            'global_id' => 'EBAY-SG',
            'alpha2Code' => 'SG',
            'language' => 'en-SG',
            'teritory' => 'SG',
            'site_name' => 'eBay Singapore',
            'ebay_site_id' => 216,
            'removed' => false,
        ),
        'ebay-us' => array(
            'global_id' => 'EBAY-US',
            'alpha2Code' => 'US',
            'language' => 'en-US',
            'teritory' => 'US',
            'site_name' => 'eBay United States',
            'ebay_site_id' => 0,
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

        return $this->globalIds[strtolower($id)]['global_id'];
    }
    /**
     * @param string $id
     * @return array|null
     */
    public function getTotalInformation(string $id): ?array
    {
        if (!$this->has($id)) {
            return null;
        }

        return $this->globalIds[strtolower($id)];
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
        if (!array_key_exists('global_id', $values) and !empty($values['global_id'])) {
            throw new \RuntimeException('GlobalId values has to be an array with \'global_id\' key and a non empty \'global_id\' value');
        }

        if ($this->has($name)) {
            throw new \RuntimeException('Global id '.$name.' already exists');
        }

        if (!array_key_exists('global_id', $values) and !empty($values['global_id'])) {
            throw new \RuntimeException('Global id '.$name.' value array has to have at least a global_id key and corresponding value');
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