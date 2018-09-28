<?php
/**
 * Created by PhpStorm.
 * User: reallyli
 * Date: 18/9/28
 * Time: 下午12:49
 */

namespace Reallyli\LaravelDeployer\Concerns;

/**
 * Class DeployBuilder
 * @package Reallyli\LaravelDeployer\Concerns
 */
trait DeployBuilder
{
    /**
     * @var string
     */
    private $deployCustomFilePath = 'deploy.yml';

    /**
     * @var string
     */
    private $deployExecutableFilePath = 'deploy.php';

    /**
     * Method description:getConfigFullPath
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @return string
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function getConfigFullPath() : string
    {
        return env('APP_ENV') === 'testing' ? $this->getTestingConfigFullPath() : base_path($this->deployCustomFilePath);
    }

    /**
     * Method description:getDeployFileFullPath
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @since 18/9/28
     * @return mixed
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function getDeployFileFullPath()
    {
        if (env('APP_ENV') === 'testing') {
            return $this->getTestingDeployFileFullPath();
        }

        $ds = DIRECTORY_SEPARATOR;

        return "vendor{$ds}{reallyli{$ds}laravel-unideploy}{$ds}.build{$ds}$this->deployExecutableFilePath";
    }

    /**
     * Method description:getTestingDeployFileFullPath
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @since 18/9/28
     * @return mixed
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function getTestingDeployFileFullPath() : string
    {
        return realpath(dirname(__DIR__, 3)) . '/tests/.build/' . $this->deployExecutableFilePath;
    }

    /**
     * Method description:
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @since 18/9/28
     * @return string
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    private function getTestingConfigFullPath() : string
    {
        return realpath(dirname(__DIR__, 3)) . '/tests/Features/' . $this->deployCustomFilePath;
    }
}
