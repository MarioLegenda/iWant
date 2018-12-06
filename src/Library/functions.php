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