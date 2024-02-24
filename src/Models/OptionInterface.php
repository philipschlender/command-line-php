<?php

namespace PhilipSchlender\CommandLine\Models;

use PhilipSchlender\CommandLine\Enumerations\OptionType;

interface OptionInterface
{
    public function getName(): string;

    public function getOptionType(): OptionType;
}
