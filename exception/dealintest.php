<?php

/* 注册Whoops提供的处理程序 */

// 使用Composer自动加载器
require '../vendor/autoload.php';

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

// 设置Whoops提供的错误和异常处理程序
$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();




$timezone = new DateTimeZone('PRC');
$dateStart = new DateTime('', $timezone);
$dateInterval = DateInterval::createFromDateString('-1 day');
$datePeriod = new DatePeriod($dateStart, $dateInterval, 30);
foreach ($datePeriod as $date) {
    $file = 'ssh2.sftp://USER:PASS@rsync.net/' . $date->format('Y-m-d') . '.log.bz2';
    if (file_exists($file)) {
        $handle2 = fopen($file, 'rb');
        stream_filter_append($handle2, 'bzip2.decompress');// 使用bzip2.decompress流过滤器可以在读取日志文件的同时自动解压缩。除此之外，我们还可以使用shell_exec()或bzdecompress()函数，手动把日志文件解压缩到临时目录中，然后迭代解压缩后的文件，等PHP脚本完成任务后再清理这些解压缩后的文件。不过，使用PHP流更简单，也更优雅。
        while (feof($handle2) !== true) {
            $line = fgets($handle2);
            if (strpos($line,'www.example.com') !== false) {
                fwrite(STDOUT, $line);
            }
        }
        fclose($handle2);
    }
}
