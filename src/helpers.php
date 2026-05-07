<?php

use Gabriel\FluentData\Collections\Collection;

if(!function_exists('collect')) {
    function collect(array $items): Collection
    {
        return new Collection($items);
    }
}