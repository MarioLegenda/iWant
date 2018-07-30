<?php

namespace App\Ebay\Library\Tools;

interface UrlifyInterface
{
    /**
     * @param int $counter
     * @return mixed
     */
    public function urlify(int $counter);
}