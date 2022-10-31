<?php

namespace Simply\Maker\Command;

class CommandsRegistry {
    private array $commands;

    public function __construct(iterable $commands) {
        $this->commands = iterator_to_array($commands);
    }

    /**
     * @return array
     */
    public function getCommands(): array {
        return $this->commands;
    }

    public function hasCommands() : bool {
        return !empty($this->commands);
    }
}
