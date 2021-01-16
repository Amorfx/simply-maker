<?php

namespace Simply\Maker\Test;

use PHPUnit\Framework\TestCase;
use Simply\Maker\FileManager;

class FileManagerTest extends TestCase {
    private $fileManager;

    public function setUp(): void {
        if (!defined('WP_CONTENT_DIR')) {
            define('WP_CONTENT_DIR', '/var/www/wordpress/wp-content');
            define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');
        }

        $this->fileManager = new FileManager();
    }

    public function testCanGetRelativeRootPath() {
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

    public function testCanCreateFile() {
        $path = 'config/posttypes/test.yml';
        $fileContent = 'test-content';
        $this->fileManager->setRootPath('plugin', 'cd-core');
        $this->fileManager->createFile($path, $fileContent);

        $this->assertFileExists($this->fileManager->getRootPath() . '/' . $path);
        $this->assertEquals($fileContent, file_get_contents($this->fileManager->getRootPath() . '/' . $path));
        unlink($this->fileManager->getRootPath() . '/' . $path);
    }

    public function testParsingTemplate() {
        $pathFile = '/tmp/test.tpl.php';
        $expectedContent = 'Hello World ok';
        $variableContent = 'Hello World';
        file_put_contents($pathFile, '<?php echo $var; ?> ok');
        $content = $this->fileManager->parseTemplate($pathFile, ['var' => $variableContent]);
        $this->assertEquals($expectedContent, $content);
        unlink($pathFile);
    }
}
