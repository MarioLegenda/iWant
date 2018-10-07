<?php

namespace App\Ebay\Library\Information;


class SellerBusinessTypeValidSitesInformation
{
    const EBAY_AT = 'EBAY-AT';
    const EBAY_CH = 'EBAY-CH';
    const EBAY_DE = 'EBAY-DE';
    const EBAY_ES = 'EBAY-ES';
    const EBAY_FR = 'EBAY-FR';
    const EBAY_FRBE = 'EBAY-FRBE';
    const EBAY_GB = 'EBAY-GB';
    const EBAY_IE = 'EBAY-IE';
    const EBAY_IT = 'EBAY-IT';
    const EBAY_NLBE = 'EBAY-NLBE';
    const EBAY_PL = 'EBAY-PL';
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
        'ebay-gb' => array(
            'global-id' => 'EBAY-GB',
            'language' => 'en-GB',
            'teritory' => 'GB',
            'site-name' => 'eBay UK',
            'ebay-site-id' => 3,
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
        'ebay-it' => array(
            'global-id' => 'EBAY-IT',
            'language' => 'it-IT',
            'teritory' => 'IT',
            'site-name' => 'eBay Italy',
            'ebay-site-id' => 101,
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
        'ebay-pl' => array(
            'global-id' => 'EBAY-PL',
            'language' => 'pl-PL',
            'teritory' => 'PL',
            'site-name' => 'eBay Poland',
            'ebay-site-id' => 212,
            'removed' => false,
        ),
    );
    /**
     * @var SellerBusinessTypeValidSitesInformation $instance
     */
    private static $instance;
    /**
     * @return SellerBusinessTypeValidSitesInformation
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
     * @return SellerBusinessTypeValidSitesInformation
     * @throws \RuntimeException
     */
    public function add(string $name, array $values) : SellerBusinessTypeValidSitesInformation
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