<?php

namespace PhilipSchlender\CommandLine\Tests;

class CommandTest extends TestCase
{
    protected string $filename;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $filename = realpath(sprintf('%s/script.php', __DIR__));

        if (!is_string($filename)) {
            throw new \Exception('Failed to find script.');
        }

        $this->filename = $filename;
    }

    public function testHandle(): void
    {
        $options = [
            '-a a',
            '-b=b',
            '-c=c',
            '-d',
            '-e',
            '-f',
            '--dry-run',
        ];

        $arguments = [
            'g',
            'h',
            'i',
        ];

        [$resultCode, $output] = $this->executeComand($this->filename, $options, $arguments);

        $this->assertIsInt($resultCode);
        $this->assertEquals(0, $resultCode);
        $this->assertIsString($output);
        $this->assertEquals("a=a\nb=b\nc=c\nd=1\ne=1\nf=1\ndry-run=1\n0=g\n1=h\n2=i\n", $output);
    }

    public function testHandleNoOptionsAndNoArguments(): void
    {
        $options = [];

        $arguments = [];

        [$resultCode, $output] = $this->executeComand($this->filename, $options, $arguments);

        $this->assertIsInt($resultCode);
        $this->assertEquals(0, $resultCode);
        $this->assertIsString($output);
        $this->assertEquals('', $output);
    }

    public function testHandleUnknownOptions(): void
    {
        $options = [
            '-g',
            '--foobar',
        ];

        $arguments = [];

        [$resultCode, $output] = $this->executeComand($this->filename, $options, $arguments);

        $this->assertIsInt($resultCode);
        $this->assertEquals(0, $resultCode);
        $this->assertIsString($output);
        $this->assertEquals('', $output);
    }

    /**
     * @param array<int,string> $options
     * @param array<int,string> $arguments
     *
     * @return array<int,int|string|null>
     *
     * @throws \Exception
     */
    protected function executeComand(string $filename, array $options, array $arguments): array
    {
        $command = sprintf('php %s', $filename);

        if (!empty($options)) {
            $command .= sprintf(' %s', implode(' ', $options));
        }

        if (!empty($arguments)) {
            $command .= sprintf(' %s', implode(' ', $arguments));
        }

        $command = escapeshellcmd($command);

        $resultCode = null;

        ob_start();

        system($command, $resultCode);

        $output = ob_get_clean();

        if (!is_string($output)) {
            throw new \Exception('Failed to get output.');
        }

        return [
            $resultCode,
            $output,
        ];
    }
}
