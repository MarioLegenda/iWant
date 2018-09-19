<?php
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 19/09/2018
 * Time: 14:17
 */

namespace App\Etsy\Library\Response\ResponseItem;


use App\Library\Infrastructure\Notation\ArrayNotationInterface;

interface ResultsInterface extends
    ArrayNotationInterface,
    \Countable,
    \IteratorAggregate,
    \ArrayAccess
{

}