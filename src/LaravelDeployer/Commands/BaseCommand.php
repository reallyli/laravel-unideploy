<?php

namespace Reallyli\LaravelDeployer\Commands;

use Illuminate\Console\Command;
use Reallyli\LaravelDeployer\Concerns\DeployBuilder;
use Reallyli\LaravelDeployer\Concerns\ParsesCliParameters;
use Reallyli\LaravelDeployer\ConfigFile;
use Reallyli\LaravelDeployer\LaravelDeployerException;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

class BaseCommand extends Command
{
    use ParsesCliParameters;
    use DeployBuilder;

    protected $parameters;
    protected $providedFile;
    protected $providedStrategy;
    protected $useDeployerOptions = true;

    public function __construct()
    {
        $deployerOptions = '
            {--s|strategy= : Default deployement strategy}
            {--p|parallel : Run tasks in parallel}
            {--l|limit= : How many host to run in parallel?}
            {--no-hooks : Run task without after/before hooks}
            {--log= : Log to file}
            {--roles= : Roles to deploy}
            {--hosts= : Host to deploy, comma separated, supports ranges [:]}
            {--o|option=* : Sets configuration option}
            {--f|file= : Specify Deployer file}
            {--tag= : Tag to deploy}
            {--revision= : Revision to deploy}
            {--branch= : Branch to deploy}
        ';

        if ($this->useDeployerOptions) {
            $this->signature .= $deployerOptions;
        }

        parent::__construct();
    }

    public function dep($command)
    {
        $this->parameters = $this->getParameters();
        $this->providedFile = $this->parameters->pull('--file');
        $this->providedStrategy = $this->parameters->pull('--strategy');

        if (! $deployFile = $this->getDeployFile()) {
            $this->error('config/deploy.php file not found.');
            $this->error('Please run `php artisan deploy:init` to get started.');

            return;
        }

        $parameters = $this->getParametersAsString($this->parameters);
        $depBinary = 'vendor'.DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'dep';
        $this->process("$depBinary --file=$deployFile $command $parameters");
    }

    public function getDeployFile()
    {
        if ($this->providedFile) {
            return $this->providedFile;
        }

        if ($customDeployFile = $this->getCustomDeployFile()) {
            return $customDeployFile;
        }

        if ($configFile = $this->getConfigFile()) {
            return $configFile
                ->toDeployFile()
                ->updateStrategy($this->providedStrategy)
                ->store();
        }
    }

    public function getConfigFile()
    {
        $filepath = $this->getConfigFullPath();
        throw_unless(file_exists($filepath), LaravelDeployerException::class, 'deploy.yml file not found!');

        try {
            $deployConfig = Yaml::parseFile($filepath);
        } catch (LaravelDeployerException $e) {
            throw $e;
        }
        throw_unless($deployConfig, LaravelDeployerException::class, 'deploy.yml is empty!');

        return new ConfigFile($deployConfig);
    }

    public function getCustomDeployFile()
    {
        $custom = $this->getConfigFile()->get('custom_deployer_file');
        if (is_string($custom) && $custom) {
            return file_exists(base_path($custom)) ? base_path($custom) : null;
        }
    }

    public function process($command)
    {
        $process = (new Process($command))
            ->setTty($this->isTtySupported())
            ->setWorkingDirectory(base_path())
            ->setTimeout(null)
            ->setIdleTimeout(null)
            ->mustRun(function ($type, $buffer) {
                $this->output->write($buffer);
            });
    }

    public function isTtySupported()
    {
        if (env('APP_ENV') === 'testing') {
            return false;
        }

        return (bool) @proc_open('echo 1 >/dev/null', [
            ['file', '/dev/tty', 'r'],
            ['file', '/dev/tty', 'w'],
            ['file', '/dev/tty', 'w'],
        ], $pipes);
    }
}
