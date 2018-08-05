<?php

namespace App\Ebay\Library\Dynamic;

use App\Ebay\Library\ItemFilter\ItemFilterInterface;
use App\Library\Helper;
use App\Library\UrlifyInterface;

class BaseDynamic implements UrlifyInterface, ItemFilterInterface
{
    /**
     * @var DynamicMetadata $dynamicMetadata
     */
    protected $dynamicMetadata;
    /**
     * @var DynamicConfiguration $dynamicConfiguration
     */
    protected $dynamicConfiguration;
    /**
     * @var DynamicErrors $errors
     */
    protected $errors;
    /**
     * BaseDynamic constructor.
     * @param DynamicMetadata $dynamicMetadata
     * @param DynamicConfiguration $dynamicConfiguration
     * @param DynamicErrors $dynamicErrors
     */
    public function __construct(
        DynamicMetadata $dynamicMetadata,
        DynamicConfiguration $dynamicConfiguration,
        DynamicErrors $dynamicErrors
    ) {
        $this->dynamicMetadata = $dynamicMetadata;
        $this->dynamicConfiguration = $dynamicConfiguration;
        $this->errors = $dynamicErrors;
    }
    /**
     * @param int $counter
     * @return string
     */
    public function urlify(int $counter = null) : string
    {
        $multipleValues = $this->dynamicConfiguration->isMultipleValues();
        $dateTime = $this->dynamicConfiguration->isDateTime();

        $dynamicValue = $this->dynamicMetadata->getDynamicValue();
        $name = $this->dynamicMetadata->getName();

        if ($multipleValues === false and $dateTime === false) {
            $dynamicValue = $this->refactorDynamicValue($dynamicValue);

            return 'itemFilter('.$counter.').name='.$name.'&itemFilter('.$counter.').value='.$dynamicValue[0].'&';
        }

        if ($multipleValues === true and $dateTime === false) {
            $toBeAppended = 'itemFilter('.$counter.').name='.$name;

            $internalCounter = 0;
            foreach ($dynamicValue as $dynamic) {
                $dynamicValue = $this->refactorDynamicValue((is_array($dynamic)) ? $dynamic : array($dynamic));

                $toBeAppended.='&itemFilter('.$counter.').value('.$internalCounter.')='.$dynamicValue[0];

                $internalCounter++;
            }

            return $toBeAppended.'&';
        }

        if ($multipleValues === false and $dateTime === true) {
            $dateTime = $dynamicValue[0];

            return 'itemFilter('.$counter.').name='.$name.'&itemFilter('.$counter.').value='.Helper::convertToGMT($dateTime).'&';
        }

        if ($multipleValues === true and $dateTime === true) {
            $toBeAppended = 'itemFilter('.$counter.').name='.$name;

            $internalCounter = 0;
            foreach ($dynamicValue as $dynamic) {
                $dynamicValue = '';
                if ($dynamic instanceof \DateTime) {
                    $dynamicValue = Helper::convertToGMT($dynamic);
                } else {
                    $dynamicValue = $this->refactorDynamicValue($dynamic);
                }

                $toBeAppended.='&itemFilter('.$counter.').value('.$internalCounter.')='.$dynamicValue[0];

                $internalCounter++;
            }

            return $toBeAppended.'&';
        }
    }
    /**
     * @return DynamicMetadata
     */
    public function getDynamicMetadata(): DynamicMetadata
    {
        return $this->dynamicMetadata;
    }
    /**
     * @param array $value
     * @param null $count
     * @return bool
     */
    public function genericValidation(array $value, $count = null) : bool
    {
        if (empty($value)) {
            $message = sprintf(
                'Argument for dynamic \'%s\' cannot be empty',
                $this->getDynamicMetadata()->getName()
            );

            $this->errors->add($message);

            return false;
        }

        if ($count !== null) {
            if (count($value) > $count) {
                $message = sprintf(
                    '\'%s\' can receive an array argument with only %d values(s)',
                    $this->getDynamicMetadata()->getName(),
                    $count
                );

                $this->errors->add($message);

                return false;
            }
        }

        if (count($value) === 1) {
            $key = array_keys($value)[0];

            if (!is_int($key)) {
                $message = sprintf(
                    'Every item filter argument has to be in an array with either one or multiple integer entries'
                );

                $this->errors->add($message);

                return false;
            }
        }

        return true;
    }
    /**
     * @param $value
     * @return bool
     */
    protected function checkBoolean($value) : bool
    {
        if (!is_bool($value)) {
            $message = sprintf(
                '\'%s\' can only accept true or false boolean values',
                $this->getDynamicMetadata()->getName()
            );

            $this->errors->add($message);

            return false;
        }

        return true;
    }
    /**
     * @param array $dynamics
     * @return array
     */
    private function refactorDynamicValue(array $dynamics)
    {
        if (count($dynamics) === 1) {
            $dynamic = $dynamics[0];
            if (is_bool($dynamics[0])) {
                return ($dynamic === true) ? array('true') : array('false');
            }
        }

        return $dynamics;
    }
}