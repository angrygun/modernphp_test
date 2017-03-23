<?php

/*
 * 添加一个Monolog处理程序，把重要的提醒或突发错误通过电子邮件发给管理员。
 *
 *      需要使用SwiftMailer组件
 * */

# 在生产环境中使用Monolog记录日志 #

// 使用Composer自动加载器
require '../vendor/autoload.php';

// 导入Monolog的命名空间
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SwiftMailerHandler;

date_default_timezone_set('PRC');

// 设置Monolog和基本的处理程序
$log = new Logger('my-app-name');
$log->pushHandler(new StreamHandler('logs/production.log', Logger::WARNING));

// 添加SwiftMailer处理程序，让它处理重要的错误
$transport = Swift_SmtpTransport::newInstance('smtp.qq.com', 465, 'ssl')
//$transport = Swift_SmtpTransport::newInstance('smtp.126.com', 25)
    ->setUsername('your qq email')
    //->setUsername('your 126 email')
    ->setPassword('qq email password');
    //->setPassword('126 email password');
$mailer = Swift_Mailer::newInstance($transport);
$message = Swift_Message::newInstance()
    ->setSubject('Website error!')
    ->setFrom(array('addresser@qq.com' => 'addresser_name'))
    //->setFrom(array('addresser@126.com' => 'addresser_name'))
    ->setTo(array('receiver@qq.com'));
$log->pushHandler(new SwiftMailerHandler($mailer, $message, Logger::CRITICAL));

// 使用日志记录器
$log->critical('The server is on fire!');
