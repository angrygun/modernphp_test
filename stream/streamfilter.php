<?php

/* 流过滤器
 *
 *PHP内置了几个流过滤器：string.rot13、string.toupper、string.tolower和string.strip_tags
 *
 * 若要把过滤器附加到现有的流上，要使用stream_filter_append()函数
 *
 */

// 演示使用流过滤器string.toupper

$handle = fopen('test.txt', 'rb');
stream_filter_append($handle, 'string.toupper');
while (feof($handle) !== true) {
    echo fgets($handle);// 输出的全是大写字母
}
fclose($handle);

echo '<hr/>';


// 演示使用php://filter附加流过滤器string.toupper

/*
 * 我们要特别注意fopen()函数的第一个参数。
 *
 * 这个参数的值是php://流封装协议的流标识符。
 * 这个流标识符中的目标如下所示：
 *      filter/read=<filter_name>/resource=<scheme>://<target>
 * 这种方式和stream_filter_append()函数相比较为繁琐，可是PHP的某些文件系统函数在调用后无法附加流过滤器，例如file()和fpassthru()。所以，使用这些函数时只能使用php://filter流封装协议附加流过滤器。
 * */

$handle1 = fopen('php://filter/read=string.toupper/resource=test.txt', 'rb');
while (feof($handle1) !== true) {
    echo fgets($handle1);// 输出的全是大写字母
}
fclose($handle1);

echo '<hr/>';

// 使用DateTime类和流过滤器迭代bzip压缩的日志文件

$timezone = new DateTimeZone('PRC');
$dateStart = new DateTime('', $timezone);
$dateInterval = DateInterval::createFromDateString('-1 day');
$datePeriod = new DatePeriod($dateStart, $dateInterval, 30);
foreach ($datePeriod as $date) {
    $file = 'sftp://USER:PASS@rsync.net/' . $date->format('Y-m-d') . '.log.bz2';
    if (file_exists($file)) {
        $handle2 = fopen($file, 'rb');
        stream_filter_append($handle2, 'bzip2.decompress');// 使用bzip2.decompress流过滤器可以在读取日志文件的同时自动解压缩。除此之外，我们还可以使用shell_exec()或bzdecompress()函数，手动把日志文件解压缩到临时目录中，然后迭代解压缩后的文件，等PHP脚本完成任务后再清理这些解压缩后的文件。不过，使用PHP流更简单，也更优雅。
        while (feof($handle2) !== true) {
            $line = fgets($handle2);
            if (strpos($line, 'www.example.com') !== false) {
                fwrite(STDOUT, $line);
            }
        }
        fclose($handle2);
    }
}