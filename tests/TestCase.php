<?php
/**
 * Created by PhpStorm.
 * User: reallyli
 * Date: 18/9/28
 * Time: 上午10:49
 */

namespace Reallyli\LaravelDeployer\Tests;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Testbench\TestCase as Orchestra;
use Reallyli\LaravelDeployer\Concerns\DeployBuilder;
use Symfony\Component\Yaml\Yaml;

/**
 * Class TestCase
 * @package Reallyli\LaravelDeployer\Tests
 */
abstract class TestCase extends Orchestra
{
    use DeployBuilder;

    /**
     * @var mixed
     */
    protected $deployYmlConfig;

    /**
     * @var string
     */
    protected $deployExecutableFileContent;

    /**
     * HttpBaseCase constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->deployYmlConfig = Yaml::parseFile(realpath(__DIR__) . '/Features/deploy.yml');
        $this->deployExecutableFileContent = app(Filesystem::class)->sharedGet($this->getDeployFileFullPath());

        parent::__construct($name, $data, $dataName);
    }

    /**
     * sep up
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->setBasePath(realpath(dirname(__DIR__)));
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return ['Reallyli\LaravelDeployer\LaravelDeployerServiceProvider'];
    }
}
