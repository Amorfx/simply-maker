<?php

namespace Simply\Maker;

use Simply\Maker\Util\ClassNameDetails;

class Generator
{
    private FileManager $fileManager;
    private array $pendingOperations;

    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
        $this->pendingOperations = [];
    }

    public function createClassNameDetails(string $fullClassName): ClassNameDetails
    {
        $classNameExploded = explode('\\', $fullClassName);
        $className = $classNameExploded[array_key_last($classNameExploded)];
        $namespace = str_replace('\\' . $className, '', $fullClassName);

        return new ClassNameDetails($className, $namespace);
    }

    public function generateClass(ClassNameDetails $classNameDetails, string $targetPath, string $template, array $variables = [])
    {
        $variables = array_merge($variables, [
            'namespace' => $classNameDetails->getNamespace(),
            'className' => $classNameDetails->getClassName(),
        ]);
        $this->addOperation($targetPath, $template, $variables);
    }

    public function generateFile(string $targetPath, string $template, array $variables = [])
    {
        $this->addOperation($targetPath, $template, $variables);
    }

    /**
     * Add operation to create files to pending
     *
     * @param string $targetPath
     * @param string $templateName
     * @param array $variables
     *
     * @throws \Exception
     */
    private function addOperation(string $targetPath, string $templateName, array $variables)
    {
        if (file_exists($targetPath)) {
            throw new \RuntimeException(sprintf('The file "%s" can\'t be generated because it already exists.', $targetPath));
        }

        $templatePath = $templateName;
        if (! file_exists($templatePath)) {
            $templatePath = __DIR__ . '/Resources/skeleton/'.$templateName;

            if (! file_exists($templatePath)) {
                throw new \Exception(sprintf('Cannot find template "%s"', $templateName));
            }
        }

        $this->pendingOperations[$targetPath] = [
            'template' => $templatePath,
            'variables' => $variables,
        ];
    }

    public function writeChanges()
    {
        foreach ($this->pendingOperations as $targetPath => $templateData) {
            $this->fileManager->dumpFile($targetPath, $this->fileManager->parseTemplate($templateData['template'], $templateData['variables']));
        }
    }
}
