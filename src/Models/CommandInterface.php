<?php

namespace PhilipSchlender\CommandLine\Models;

interface CommandInterface
{
    public function handle(): int;
}
