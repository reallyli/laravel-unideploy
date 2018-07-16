<?php

namespace Reallyli\LaravelDeployer\Commands;

class Deploy extends BaseCommand
{
    protected $signature = 'deploy {stage? : Stage or hostname}';
    protected $description = 'Deploy your application';

    public function handle()
    {
        $this->dep('deploy');
    }
}