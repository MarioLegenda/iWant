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

function apply_on_iterable(iterable $iterable, \Closure $callback)
{
    $newResult = [];

    $iterableGenerator = Util::createGenerator($iterable);
    foreach ($iterableGenerator as $entry) {
        $newResult[] = $callback->__invoke($entry['item']);
    }

    return $newResult;
}