<?php

use PhilipSchlender\CommandLine\Tests\Command;

require_once realpath(sprintf('%s/../vendor/autoload.php', __DIR__));

$command = new Command();

exit($command->handle());
