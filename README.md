# 🚀 Laravel Project Deployer

[![StyleCI](https://github.styleci.io/repos/141083390/shield?branch=master)](https://github.styleci.io/repos/141083390)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/reallyli/laravel-unideploy/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/reallyli/laravel-unideploy/?branch=master)
[![Build Status](https://travis-ci.org/reallyli/laravel-unideploy.svg?branch=master)](https://travis-ci.org/reallyli/laravel-unideploy)

![](https://raw.githubusercontent.com/wiki/reallyli/laravel-unideploy/laravel-unideploy-config.jpg)

> A Deployer-based laravel project deployment extension package.

# Installation

```shell
composer require reallyli/laravel-unideploy --dev
```

## Start

```shell
php artisan deploy:init
```

## Deploy

Do not specify a deployment branch by default is the current branch

```shell
php artisan deploy {staging} --branh={branch}
```

## Rollback

Roll back the current version

```shell
php artisan deploy:rollback {staging}
```

## Unlock

Temporarily unlock the deployment file

```shell
php artisan deoloy:unlock {staging} 
```

## Links

* [Deployer — Deployment tool for PHP](https://deployer.org/)

