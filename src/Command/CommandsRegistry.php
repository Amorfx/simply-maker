<?php

namespace Simply\Maker\Command;

use Symfony\Component\Console\Command\Command;

class CommandsRegistry
{
    /**
     * @var Command[]
     */
    private array $commands;

    /**
     * @param iterable<Command> $commands
     */
    public function __construct(iterable $commands)
    {
        $this->commands = iterator_to_array($commands); // @phpstan-ignore-line
    }

    /**
     * @return Command[]
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    public function hasCommands(): bool
    {
        return ! empty($this->commands);
    }
}
