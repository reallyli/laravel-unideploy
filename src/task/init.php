<?php

namespace Deployer;

set('user', function () {
    return runLocally('git config --get user.name');
});

set('environment', function () {
    return input()->getArgument('stage') ?: get('default_stage');
});

set('branch', function () {
    return input()->getOption('branch') ?: runLocally('git symbolic-ref --short -q HEAD');
});

set('last_commit', function () {
    return run('cd {{release_path}} && git rev-parse HEAD');
});

desc('Initialize the environment configuration');
task('init:environment', function () {
    run('cd {{release_path}} && cat .env.' . get('environment') . ' >> ' . get('env_path'));
});

desc('Record revision log');
task('record:revision:log', function () {
    $filePath = get('deploy_path') . '/revision.log';
    $date = '[' . date('Y-m-d H:i:s'). ']';
    $revisionMessage = join(',', [
        'branch:' . get('branch'),
        'environment:' . get('environment'),
        'user:' . get('user'),
        'last_commit_id:' . get('last_commit'),
    ]);

    run('echo ' . $date . $revisionMessage . ' >> ' . $filePath);
    writeln('record revision log successfully');
});
