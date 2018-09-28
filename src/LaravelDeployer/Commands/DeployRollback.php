<?php

namespace Reallyli\LaravelDeployer\Commands;

class DeployRollback extends BaseCommand
{
    protected $signature = 'deploy:rollback {stage? : Stage or hostname}';
    protected $description = 'Rollback to previous release';

    public function handle()
    {
        if ($this->confirm('Are you sure rollback to previous release')) {
            $this->dep('rollback');
        }
    }
}
