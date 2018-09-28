<?php

namespace Reallyli\LaravelDeployer\Tests;

use Illuminate\Filesystem\Filesystem;
use Reallyli\LaravelDeployer\ConfigFile;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlParseTest
 * @package Reallyli\LaravelDeployer\Tests
 */
class YamlParseTest extends TestCase
{
    /**
     * Method description:testConfigFile
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @since 18/9/28
     * @param
     * @return mixed
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function testConfigFile()
    {
        $configService = new ConfigFile($this->deployYmlConfig);

        $this->assertSame($this->getConfigFullPath(), $configService->store());
    }

    /**
     * Method description:testDeployFile
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @since 18/9/28
     * @return mixed
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function testDeployFile()
    {
        $deployConfig = $this->deployYmlConfig;
        array_set($deployConfig, 'options.application', 'LaravelDeployer');
        $deployConfigToYml = Yaml::dump($deployConfig);

        app(Filesystem::class)->put($this->getConfigFullPath(), $deployConfigToYml);

        $this->assertTrue(str_contains($this->deployExecutableFileContent, 'LaravelDeployer'));
    }
}
