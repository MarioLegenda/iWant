<?php

namespace App\Library\Infrastructure;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

interface CollectionInterface extends
    \IteratorAggregate,
    \ArrayAccess,
    \Countable,
    ServiceFilterInterface,
    GeneratorInterface,
    ArrayNotationInterface {}