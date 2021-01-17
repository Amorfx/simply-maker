<?php

namespace Simply\Maker\Util;

class ClassNameDetails {
    private string $className;
    private string $namespace;

    public function __construct(string $className, string $namespace) {
        $this->className = $className;
        $this->namespace = $namespace;
    }

    public function getFullName(): string {
        return $this->namespace . '\\' . $this->className;
    }

    public function getNamespace(): string {
        return $this->namespace;
    }

    public function getClassName(): string {
        return $this->className;
    }
}
