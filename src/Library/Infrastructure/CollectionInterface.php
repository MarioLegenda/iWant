<?php

namespace App\Library\Infrastructure;

interface CollectionInterface extends
    \IteratorAggregate,
    \Countable,
    ServiceFilterInterface,
    GeneratorInterface {}