<?php

date_default_timezone_set('PRC');
$datetime = new DateTime('2014-01-01 14:00:00');// 创建DateTime实例
$interval = new DateInterval('P2W');// 创建长度为两周的间隔
$datetime->add($interval);// 修改DateTime实例
echo $datetime->format('Y-m-d H:i:s'), PHP_EOL;

$timezone = new DateTimeZone('UTC');
$datetime->setTimeZone($timezone);

echo $datetime->format('Y-m-d H:i:s');

echo '<hr/>';

/* 创建反向的DateInterval实例 */

$dateStart = new DateTime();
$dateInterval = DateInterval::createFromDateString('-1 day');
$datePeriod = new DatePeriod($dateStart, $dateInterval, 3);
foreach ($datePeriod as $date) {
    echo $date->format('Y-m-d'), PHP_EOL;
}

echo '<hr/>';

$start = new DateTime();
$period = new DatePeriod($start, $interval, 3, DatePeriod::EXCLUDE_START_DATE);
foreach ($period as $nextDateTime) {
    echo $nextDateTime->format('Y-m-d H:i:s'), PHP_EOL;
}