<?php

namespace PhilipSchlender\CommandLine\Models;

use PhilipSchlender\CommandLine\Enumerations\OptionType;

class Option implements OptionInterface
{
    public function __construct(protected string $name, protected OptionType $optionType)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOptionType(): OptionType
    {
        return $this->optionType;
    }
}
