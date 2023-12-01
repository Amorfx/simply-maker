<?php

namespace Simply\Maker\Test;

use PHPUnit\Framework\TestCase;
use Simply\Maker\FileManager;

class FileManagerTest extends TestCase
{
    private $fileManager;

    public function setUp(): void
    {
        if (! defined('WP_CONTENT_DIR')) {
            define('WP_CONTENT_DIR', '/var/www/wordpress/wp-content');
            define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');
        }

        $this->fileManager = new FileManager();
    }

    public function testCanGetRelativeRootPath()
    {
        $rootTypePlugin = 'plugin';
        $rootTypeTheme = 'theme';
        $rootTypeWrong = 'root';

        $this->fileManager->setRootPath($rootTypePlugin, 'cd-core');
        $this->assertStringContainsString('wp-content/plugins/cd-core', $this->fileManager->getRootPath());

        $this->fileManager->setRootPath($rootTypeTheme, 'mytheme');
        $this->assertStringContainsString('wp-content/themes/mytheme', $this->fileManager->getRootPath());

        $this->expectException(\RuntimeException::class);
        $this->fileManager->setRootPath($rootTypeWrong, 'mytheme');
    }

    public function testParsingTemplate()
    {
        $pathFile = '/tmp/test.tpl.php';
        $expectedContent = 'Hello World ok';
        $variableContent = 'Hello World';
        file_put_contents($pathFile, '<?php echo $var; ?> ok');
        $content = $this->fileManager->parseTemplate($pathFile, ['var' => $variableContent]);
        $this->assertEquals($expectedContent, $content);
        unlink($pathFile);
    }

    public function testGetAllAvailableDirectories()
    {
        $tmpPluginDir = '/tmp/plugins';
        if (! file_exists($tmpPluginDir)) {
            mkdir($tmpPluginDir);
        }

        $allPlugins = ['a', 'b', 'c'];
        foreach ($allPlugins as $namePlugin) {
            $path = $tmpPluginDir . '/' . $namePlugin;
            if (! file_exists($path)) {
                mkdir($tmpPluginDir . '/' . $namePlugin);
            }
        }

        $this->assertEqualsCanonicalizing($allPlugins, $this->fileManager->getAvailableDirectories($tmpPluginDir));
        system('rm -rf -- ' . escapeshellarg($tmpPluginDir));
    }
}
