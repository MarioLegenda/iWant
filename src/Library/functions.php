<?php

use App\Library\Util\Util;

function to_float($num) {
    $dotPos = strrpos($num, '.');
    $commaPos = strrpos($num, ',');
    $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
        ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

    if (!$sep) {
        return floatval(preg_replace("/[^0-9]/", "", $num));
    }

    return floatval(
        preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
        preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
    );
}

function map_reduce(iterable $iterable, \Closure $callback): array
{
    $iterableGen = Util::createGenerator($iterable);

    $result = [];
    foreach ($iterableGen as $entry) {
        $key = $entry['key'];
        $item = $entry['item'];

        $product = $callback->__invoke($item);

        $result[] = $product;
    }

    return $result;
}

function get_value_or_null(array $item, $key)
{
    return (isset($item[$key])) ? $item[$key] : null;
}

function advanced_array_filter(array $array, \Closure $callback): array
{
    $retainedValues = [];
    $arrayGen = Util::createGenerator($array);
    $result = [];

    foreach ($arrayGen as $entry) {
        $item = $entry['item'];

        $product = $callback->__invoke($item, $retainedValues);

        if ($product === true) {
            $result[] = $item;
        }
    }

    $retainedValues = [];

    return $result;
}

function utf8_clean_array_merge(...$arrays): array
{
    $arrayGen = Util::createGenerator($arrays);

    $newArray = [];
    foreach ($arrayGen as $entry) {
        $itemArray = $entry['item'];

        if (!is_array($itemArray)) {
            $message = sprintf(
                'The only argument passed to this function must be an array with every entry an array'
            );

            throw new \RuntimeException($message);
        }

        $itemArrayGen = Util::createGenerator($itemArray);

        foreach ($itemArrayGen as $itemEntry) {
            $itemArrayItem = $itemEntry['item'];

            $newArray[] = $itemArrayItem;
        }
    }

    return $newArray;
}

function apply_on_iterable(iterable $iterable, \Closure $callback)
{
    $newResult = [];

    $iterableGenerator = Util::createGenerator($iterable);
    foreach ($iterableGenerator as $entry) {
        $newResult[] = $callback->__invoke($entry['item'], $entry['key']);
    }

    return $newResult;
}

function utf8ize($mixed)
{
    if (is_array($mixed)) {
        $gen = Util::createGenerator($mixed);

        foreach ($gen as $entry) {
            $value = $entry['item'];
            $key = $entry['key'];

            $mixed[$key] = utf8ize($value);
        }
    } elseif (is_string($mixed)) {
        return mb_convert_encoding($mixed, "UTF-8", "UTF-8");
    }

    return $mixed;
}

function strToLowerWithEncoding(string $str)
{
    return mb_strtolower($str, mb_detect_encoding($str));
}

function jsonEncodeWithFix($toEncode, $iterations = 1, $maxIterations = 5): ?string
{
    if ($iterations === $maxIterations) {
        return null;
    }

    $encodedSearchResponse = json_encode($toEncode);

    if ($encodedSearchResponse === false) {
        $encodedSearchResponse = utf8ize($toEncode);

        $encodedSearchResponse = json_encode($encodedSearchResponse);

        if ($encodedSearchResponse === false) {
            jsonEncodeWithFix($toEncode, ++$iterations);
        }
    }

    return $encodedSearchResponse;
}

function stringToBool($bool, bool $throwException = false): bool
{
    if (is_bool($bool)) {
        return $bool;
    }

    if (is_string($bool)) {
        if ($bool !== 'true' and $bool !== 'false') {
            if ($throwException) {
                $message = sprintf(
                    'Non valid \'boolean\' value given. Value should be a string \'true\' or \'false\''
                );

                throw new \RuntimeException($message);
            }

            return false;
        }
    }

    return ($bool === 'true') ? true : false;
}

function createObjectFromArrayArgs(array $args, string $objectString)
{
    return new $objectString(...$args);
}

function createBulkObjectFromArrayArgs(array $bulkArgs, string $objectString)
{
    $bulkArgsGen = (function(array $bulkArgs): \Generator {
        foreach ($bulkArgs as $arg) {
            yield [$arg];
        }
    })($bulkArgs);

    $objects = [];
    foreach ($bulkArgsGen as $item) {
        $objects[] = new $objectString(...$item);
    }

    return $objects;
}

function wrapPossibleFatalError(\Closure $closure, array $args = null)
{
    try {
        if (is_array($args)) {
            return $closure->__invoke(...$args);
        }

        return $closure->__invoke();
    } catch (Error $e) {
        return null;
    }
}