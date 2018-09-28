<?php

namespace Reallyli\LaravelDeployer;

use Symfony\Component\Yaml\Yaml;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Support\Arrayable;
use Reallyli\LaravelDeployer\Concerns\RendersCode;
use Reallyli\LaravelDeployer\Concerns\DeployBuilder;

class ConfigFile implements Arrayable
{
    use RendersCode;
    use DeployBuilder;

    const REPLACEMENT_KEYS = [
        'default',
        'strategies',
        'hooks.start',
        'hooks.build',
        'hooks.ready',
        'hooks.done',
        'hooks.success',
        'hooks.fail',
        'options',
        'hosts',
        'localhost',
        'include',
        'custom_deployer_file',
    ];

    protected $configs;
    protected $filesystem;

    public function __construct($configs)
    {
        $this->configs = collect($configs);
        $this->filesystem = app(Filesystem::class);
    }

    /**
     * Return the config file as a string after it has been parsed
     * from the `config.stub` file with the `configs` property.
     *
     * @return string
     */
    public function __toString()
    {
        $ds = DIRECTORY_SEPARATOR;
        $stub = $this->filesystem->get(__DIR__."{$ds}stubs{$ds}config.stub");

        foreach (static::REPLACEMENT_KEYS as $key) {
            $indent = substr_count($key, '.') + 1;
            $value = $this->render(array_get($this->configs, $key), $indent);
            $stub = preg_replace('/{{'.$key.'}}/', $value, $stub);
        }

        return $stub;
    }

    public function get($key, $default = null)
    {
        return $this->configs->get($key, $default);
    }

    public function toArray()
    {
        return $this->configs->toArray();
    }

    public function toDeployFile()
    {
        return new DeployFile($this->configs->toArray());
    }

    /**
     * Method description:toYml.
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @since 18/9/28
     * @return mixed
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function toYaml() : string
    {
        return Yaml::dump($this->toArray());
    }

    /**
     * Parse the `config.stub` file and copy its content onto a new
     * `deploy.php` file in the config folder of the Laravel project.
     *
     * @return string
     */
    public function store()
    {
        $path = $this->getConfigFullPath();

        $this->filesystem->put($path, $this->toYaml());

        return $path;
    }
}
