<?php
/**
 * Created by PhpStorm.
 * User: KOH
 * Date: 16/2/5
 * Time: 下午11:22
 */
use koh\framework\K;
use demo\models\TestModel;

define('APP_ROOT', __DIR__ . '/../app');

$sysConfig = include APP_ROOT . '/configs/sys.config.php';

error_reporting($sysConfig['err_reporting_level']);
date_default_timezone_set($sysConfig['default_timezone']);

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../');

$defaultNamespaces = [
    "koh\\framework" => 'framework'
];
foreach (array_keys($defaultNamespaces) as $namespace) {
    spl_autoload_register(function($class) use ($defaultNamespaces, $namespace) {
        if (strpos($class, $namespace . "\\") === 0) {
            @include str_replace("\\",'/',$defaultNamespaces[$namespace]."\\".substr($class . '.php',strlen($namespace) + 1));
        }
    });
}
spl_autoload_register(function($class) use ($sysConfig) {
    if (strpos($class, $sysConfig['app_namespace'] . "\\") === 0) {
        @include str_replace("\\",'/',"app\\".substr($class . '.php',strlen($sysConfig['app_namespace']) + 1));
    }
});

