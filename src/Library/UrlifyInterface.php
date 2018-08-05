<?php

namespace App\Library;

interface UrlifyInterface
{
    /**
     * @param int $counter
     * @return mixed
     */
    public function urlify(int $counter = null);
}