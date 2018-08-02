<?php

namespace App\Ebay\Library;

class Helper
{
    public static function compareFloatNumbers($float1, $float2, $operator='=')
    {
        // Check numbers to 5 digits of precision  
        $epsilon = 0.00001;

        $float1 = (float)$float1;
        $float2 = (float)$float2;

        switch ($operator)
        {
            // equal  
            case "=":
            case "eq":
            {
                if (abs($float1 - $float2) < $epsilon) {
                    return true;
                }
                break;
            }
            // less than  
            case "<":
            case "lt":
            {
                if (abs($float1 - $float2) < $epsilon) {
                    return false;
                }
                else
                {
                    if ($float1 < $float2) {
                        return true;
                    }
                }
                break;
            }
            // less than or equal  
            case "<=":
            case "lte":
            {
                if (self::compareFloatNumbers($float1, $float2, '<') || self::compareFloatNumbers($float1, $float2, '=')) {
                    return true;
                }
                break;
            }
            // greater than  
            case ">":
            case "gt":
            {
                if (abs($float1 - $float2) < $epsilon) {
                    return false;
                }
                else
                {
                    if ($float1 > $float2) {
                        return true;
                    }
                }
                break;
            }
            // greater than or equal  
            case ">=":
            case "gte":
            {
                if (self::compareFloatNumbers($float1, $float2, '>') || self::compareFloatNumbers($float1, $float2, '=')) {
                    return true;
                }
                break;
            }
            case "<>":
            case "!=":
            case "ne":
            {
                if (abs($float1 - $float2) > $epsilon) {
                    return true;
                }
                break;
            }
            default:
            {
                die("Unknown operator '".$operator."' in compareFloatNumbers()");
            }
        }

        return false;
    }
    /**
     * @param \DateTime $dateTime
     */
    public static function convertToGMT(\DateTime $dateTime)
    {
        return gmdate('Y-m-d\TH:i:s.', $dateTime->getTimestamp()).substr($dateTime->getTimestamp(), 0, 3).'Z';
    }
}