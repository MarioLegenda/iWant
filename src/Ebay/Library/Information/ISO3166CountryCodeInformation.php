<?php

namespace App\Ebay\Library\Information;

use Symfony\Component\Yaml\Yaml;

class ISO3166CountryCodeInformation
{
    /**
     * @var array $countryCodes
     */
    private $countryCodes = array();
    /**
     * @var ISO3166CountryCodeInformation $instance
     */
    private static $instance;
    /**
     * @return ISO3166CountryCodeInformation
     */
    public static function instance()
    {
        self::$instance = (self::$instance instanceof self) ? self::$instance : new self();

        self::$instance->createCodes();

        return self::$instance;
    }
    /**
     * @param string $code
     * @return mixed|null
     */
    public function get(string $code) : array
    {
        if (!$this->has($code)) {
            return null;
        }

        foreach ($this->countryCodes as $codes) {
            foreach ($codes as $key => $c) {
                if ($c === strtoupper($code)) {
                    return $codes;
                }
            }
        }

        return null;
    }
    /**
     * @param string $id
     * @param bool $returnRemoved
     * @return bool
     */
    public function has(string $id, bool $returnRemoved = false)
    {
        $filtered = array_filter($this->countryCodes, function ($codes) use ($id, $returnRemoved) {
            foreach ($codes as $code) {
                if ((string) $code === strtoupper($id)) {
                    if ($returnRemoved) {
                        if ($codes['removed'] === true) {
                            return true;
                        }
                    }
                    if ($codes['removed'] === false) {
                        return true;
                    }
                }
            }

            return false;
        });

        return !empty($filtered);
    }
    /**
     * @param array $values
     * @return ISO3166CountryCodeInformation
     * @throws \RuntimeException
     */
    public function add(array $values) : ISO3166CountryCodeInformation
    {
        if (!array_key_exists('alpha2', $values)) {
            throw new \RuntimeException('ISO 3166 country code has to contain at least a two character string that represents a country called \'alpha2\'. Please, refer to https://www.iso.org/obp/ui/#search');
        }

        if ($this->has($values['alpha2'])) {
            throw new \RuntimeException('Country code'.$values['alpha2'].' already exists');
        }

        if (!array_key_exists('global-id', $values) and !empty($values['global-id'])) {
            throw new \RuntimeException('Country code '.$values['alpha2'].' value array has to have at least a global-id key and corresponding value');
        }

        if ($this->has($values['alpha2'], true)) {
            $index = $this->getIndex($values['alpha2']);

            if ($index !== -1) {
                $values['removed'] = false;
                $this->countryCodes[$index] = $values;
            }
        } else {
            $values['removed'] = false;
            $this->countryCodes[] = $values;
        }

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
        
        $index = $this->getIndex($id);
        
        if ($index !== -1) {
            $this->countryCodes[$index]['removed'] = true;
            
            return true;
        }

        return false;
    }
    /**
     * @return array
     */
    public function getAll() 
    {
        return $this->countryCodes;
    }

    private function createCodes()
    {
        if (!empty($this->countryCodes)) {
            return;
        }

        $this->countryCodes = Yaml::parse(file_get_contents(__DIR__.'/country_codes.yml'))['iso-codes'];

        foreach ($this->countryCodes as $key => $codes) {
            $this->countryCodes[$key]['removed'] = false;
        }
    }
    
    private function getIndex(string $code) : int 
    {
        foreach ($this->countryCodes as $index => $codes) {
            foreach ($codes as $key => $c) {
                if ($c === strtoupper($code)) {
                    return $index;
                }
            }
        }
        
        return -1;
    }
}