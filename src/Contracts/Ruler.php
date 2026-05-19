<?php

namespace Gabriel\FluentData\Contracts;

use Gabriel\FluentData\Validation\Rule;

interface Ruler
{
    public function rule(): Rule;
}
