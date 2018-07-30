<?php

namespace App\Ebay\Library\Information;

class PaymentMethodInformation implements InformationInterface
{
    const PAY_PAL = 'PayPal';
    const PAISA_PAL = 'PaisaPal';
    const PAISA_PAY_EMI = 'PaisaPayEmi';
    /**
     * @var array $paymentMethods
     */
    private $paymentMethods = array(
        'PayPal',
        'PaisaPal',
        'PaisaPayEMI',
    );
    /**
     * @var PaymentMethodInformation $instance
     */
    private static $instance;
    /**
     * @return PaymentMethodInformation
     */
    public static function instance()
    {
        self::$instance = (self::$instance instanceof self) ? self::$instance : new self();

        return self::$instance;
    }
    /**
     * @param string $method
     * @return mixed
     */
    public function has(string $method) : bool
    {
        return in_array($method, $this->paymentMethods) !== false;
    }
    /**
     * @param string $method
     * @return PaymentMethodInformation
     */
    public function add(string $method)
    {
        if ($this->has($method)) {
            return null;
        }

        $this->paymentMethods[] = $method;

        return $this;
    }
    /**
     * @param string $entry
     * @return bool
     */
    public function remove(string $entry): bool
    {
        $position = array_search($entry, $this->paymentMethods);

        if (array_key_exists($position, $this->paymentMethods)) {
            unset($this->paymentMethods[$position]);

            return true;
        }

        return false;
    }
    /**
     * @return array
     */
    public function getAll() : array
    {
        return $this->paymentMethods;
    }
}