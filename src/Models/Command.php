<?php

namespace PhilipSchlender\CommandLine\Models;

use PhilipSchlender\CommandLine\Enumerations\OptionType;
use PhilipSchlender\CommandLine\Exceptions\CommandLineException;

abstract class Command implements CommandInterface
{
    /**
     * @var array<string,string>
     */
    protected array $optionValues;

    /**
     * @var array<int,string>
     */
    protected array $argumentValues;

    /**
     * @param array<int,OptionInterface> $options
     */
    public function __construct(protected array $options)
    {
        $this->parseOptionAndArgumentValues();
    }

    abstract public function handle(): int;

    /**
     * @throws CommandLineException
     */
    protected function parseOptionAndArgumentValues(): void
    {
        global $argv;

        $argumentIndex = null;
        $shortOptions = '';
        $longOptions = [];

        foreach ($this->options as $option) {
            switch ($option->getOptionType()) {
                case OptionType::NoValue:
                    if (1 === strlen($option->getName())) {
                        $shortOptions .= $option->getName();
                    } else {
                        $longOptions[] = $option->getName();
                    }
                    break;

                case OptionType::OptionalValue:
                    if (1 === strlen($option->getName())) {
                        $shortOptions .= sprintf('%s::', $option->getName());
                    } else {
                        $longOptions[] = sprintf('%s::', $option->getName());
                    }
                    break;

                case OptionType::RequiredValue:
                    if (1 === strlen($option->getName())) {
                        $shortOptions .= sprintf('%s:', $option->getName());
                    } else {
                        $longOptions[] = sprintf('%s:', $option->getName());
                    }
                    break;

                default:
                    throw new CommandLineException(sprintf('The option-type \'%s\' is not supported.', $option->getOptionType()->name));
            }
        }

        $options = getopt($shortOptions, $longOptions, $argumentIndex);

        if (!is_array($options)) {
            throw new CommandLineException('Failed to parse options and arguments.');
        }

        $arguments = array_slice($argv, $argumentIndex);

        $this->optionValues = [];

        foreach ($options as $name => $option) {
            $this->optionValues[$name] = is_string($option) ? $option : '1';
        }

        $this->argumentValues = $arguments;
    }

    protected function hasOptionValues(): bool
    {
        return !empty($this->optionValues);
    }

    /**
     * @return array<string,string>
     */
    protected function getOptionValues(): array
    {
        return $this->optionValues;
    }

    protected function hasOptionValue(string $name): bool
    {
        return isset($this->optionValues[$name]);
    }

    /**
     * @throws CommandLineException
     */
    protected function getOptionValue(string $name): string
    {
        if (!$this->hasOptionValue($name)) {
            throw new CommandLineException(sprintf('The option \'%s\' is not present.', $name));
        }

        return $this->optionValues[$name];
    }

    protected function hasArgumentValues(): bool
    {
        return !empty($this->argumentValues);
    }

    /**
     * @return array<int,string>
     */
    protected function getArgumentValues(): array
    {
        return $this->argumentValues;
    }

    protected function echo(string $string): void
    {
        echo sprintf("%s\n", $string);
    }
}
