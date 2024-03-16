<?php

namespace PhilipSchlender\CommandLine\Enumerations;

enum OptionType: int
{
    case NoValue = 0;
    case OptionalValue = 1;
    case RequiredValue = 2;
}
