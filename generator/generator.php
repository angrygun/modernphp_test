<?php

/*生成器*/
function myGenerator()
{
    yield 'value1';
    yield 'value2';
    yield 'value3';
}

foreach (myGenerator() as $yieldValue) {
    echo $yieldValue, PHP_EOL;
}

echo '<hr/>';

/*生成一个范围内的数值（错误方式）:没有善用内存，因为makeRange()函数要为预先创建的一个由一百万个整数组成的数组分配内存.*/

/*function makeRange($length)
{
    $dataset=[];
    for ($i = 0; $i < $length; $i++) {
        $dataset[] = $i;
    }

    return $dataset;
}

$customRange = makeRange(1000000);
foreach ($customRange as $i) {
    echo $i, PHP_EOL;
}*/

/*生成一个范围内的数值（正确方式）*/

function makeRange($length)
{
    for ($i = 0; $i < $length; $i++) {
        yield $i;
    }
}

foreach (makeRange(1000000) as $i) {
    echo $i, PHP_EOL;
}


echo '<hr/>';

/*
 * 使用生成器处理CSV文件
 *
 * 假设我们想迭代一个大小为4GB的CSV(Comma-Separated Value的简称，由逗号分隔的值)文件，而虚拟私有服务器（Virtual Private Server, VPS)只允许PHP使用1GB内存，因此不能把整个文件都加载到内存中。
 *
 * 下面示例一次只会为CSV文件中的一行分配内存，而不会把整个4GB的CSV文件都读取到内存中。
*/

function getRows($file)
{
    $handle = fopen($file, 'rb');
    if ($handle === false) {
        throw new Exception();
    }
    while (feof($handle) === false) {
        yield fgetcsv($handle);
    }
    fclose($handle);
}

foreach (getRows('data1.csv') as $row) {
    print_r($row);
}