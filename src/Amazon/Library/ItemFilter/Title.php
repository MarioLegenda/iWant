<?php

namespace App\Amazon\Library\ItemFilter;

use App\Amazon\Library\Dynamic\BaseDynamic;

class Title extends BaseDynamic implements ItemFilterInterface
{
    public function validateDynamic(): bool
    {
    }
}