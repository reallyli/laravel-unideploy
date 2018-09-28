<?php
/**
 * Created by PhpStorm.
 * User: reallyli
 * Date: 18/9/28
 * Time: 下午3:24.
 */

namespace Reallyli\LaravelDeployer\Commands;

class DeployUnlock extends BaseCommand
{
    protected $signature = 'deploy:unlock {stage? : Stage or hostname}';
    protected $description = 'Unlock dep';

    public function handle()
    {
        $this->dep('deploy:unlock');
    }
}
