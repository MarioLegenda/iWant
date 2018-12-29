<?php
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 2018-12-29
 * Time: 16:09
 */

namespace App\Library\Result;


class Result implements ResultInterface
{
    /**
     * @var iterable $result
     */
    private $result;
    /**
     * Result constructor.
     * @param iterable $result
     */
    public function __construct(iterable $result)
    {
        $this->result = $result;
    }

    public function getResult(): iterable
    {
        return $this->result;
    }
}