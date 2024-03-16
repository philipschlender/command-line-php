<?php

namespace PhilipSchlender\CommandLine\Tests;

use PhilipSchlender\CommandLine\Enumerations\OptionType;
use PhilipSchlender\CommandLine\Models\Command as BaseCommand;
use PhilipSchlender\CommandLine\Models\Option;

class Command extends BaseCommand
{
    public function __construct()
    {
        $options = [
            new Option('a', OptionType::RequiredValue),
            new Option('b', OptionType::RequiredValue),
            new Option('c', OptionType::OptionalValue),
            new Option('d', OptionType::OptionalValue),
            new Option('e', OptionType::NoValue),
            new Option('f', OptionType::NoValue),
            new Option('dry-run', OptionType::NoValue),
        ];

        parent::__construct($options);
    }

    public function handle(): int
    {
        foreach ($this->getOptionValues() as $name => $value) {
            $this->echo(sprintf('%s=%s', $name, $value));
        }

        foreach ($this->getArgumentValues() as $key => $value) {
            $this->echo(sprintf('%s=%s', $key, $value));
        }

        return 0;
    }
}
