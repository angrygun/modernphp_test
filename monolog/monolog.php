<?php
/**
 * Created by coder meng.
 * User: coder meng
 * Date: 2016/12/10 15:33
 */
require '../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

date_default_timezone_set('PRC');
//准备日志记录器
$log = new Logger('myApp');
$log->pushHandler(new StreamHandler('logs/devolopment.log',Logger::DEBUG));
$log->pushHandler(new StreamHandler('logs/production.log',Logger::WARNING));

//使用日志记录器
$log->debug('This is a debug message');
$log->warning('This is a warning message');