<?php

namespace Deployer;

date_default_timezone_set('Asia/Shanghai');

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

set('last_commit_date', function () {
    return run('cd {{release_path}} && git log -1 --format=%cd');
});

set('last_commit_author', function () {
    return run('cd {{release_path}} && git log -1 --format=%an');
});

/**
 * @param string $behavior
 * @param string $filename
 * @return mixed
 */
function recordOperationLog($behavior, $filename)
{
    $revisionMessage = implode(',', [
        '📝['.date('Y-m-d H:i:s').']',
        'deployer:'.get('user'),
        'behavior:'.$behavior,
        'branch:'.get('branch'),
        'environment:'.get('environment'),
        'last_commit_id:'.get('last_commit'),
        'last_commit_date:'.get('last_commit_date'),
        'last_commit_author:'.get('last_commit_author'),
    ]);
    run('echo '.$revisionMessage.' >> '.get('deploy_path').'/'.$filename.'.log');
    writeln('📝 record '.$behavior.' log successfully ✔');
}

desc('Record revision log');
task('record:revision:log', function () {
    recordOperationLog('release', 'revision');
});

desc('Record rollback log');
task('record:rollback:log', function () {
    recordOperationLog('rollback', 'rollback');
});
