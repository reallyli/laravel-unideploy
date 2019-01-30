<?php

namespace Deployer;

set('log_lines', function () {
    return get('log_line', 200);
});

set('log_file_name', function () {
    return get('log_file_name', 'laravel.log');
});

set('log_command', 'cat storage/logs/{{log_file_name}} | grep -Ev "^#[[:digit:]]|^\[stacktrace\]$|^\"\}$" | tail -n {{log_lines}}');

desc('Read logs from a given host');
task('logs', function () {
    writeln(run('cd {{deploy_path}}/current && {{log_command}}'));
})->shallow();
