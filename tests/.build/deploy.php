<?php

namespace Deployer;

require 'recipe/laravel-deployer.php';

/*
 * Includes
 */

/*
 * Options
 */

set('strategy', 'basic');
set('application', 'LaravelDeployer');
set('keep_releases', 6);
set('php_fpm_service', 'php7.2-fpm');
set('group_notify', false);
set('notify_channel_url', null);
set('repository', null);
set('shared_dirs', null);
set('log_file_name', 'laravel.log');
set('log_lines', 200);

/*
 * Hosts and localhost
 */

/*
 * Strategies
 */

/*
 * Hooks
 */

after('hook:ready', 'artisan:view:clear');
after('hook:ready', 'artisan:cache:clear');
after('hook:ready', 'artisan:config:cache');
after('hook:ready', 'artisan:optimize');
after('hook:done', 'fpm:reload');
after('success', 'record:revision:log');