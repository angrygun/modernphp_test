<?php
/**
 * Created by coder meng.
 * User: coder meng
 * Date: 2016/12/20 18:16
 */

/* 在生产环境中使用Monolog记录日志 */

// 使用Composer自动加载器
require '../vendor/autoload.php';

// 导入Monolog的命名空间
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

date_default_timezone_set('PRC');
// 设置Monolog提供的日志记录器
$log = new Logger('my-app-name');
$log->pushHandler(new StreamHandler('logs/my.log', Logger::WARNING));// 现在，Monolog的日志记录器会把Logger::WARNING以上等级的日志消息吸入logs/my.log文件。

$log->warning('This is test information');