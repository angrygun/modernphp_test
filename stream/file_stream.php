<?php

/* 使用HTTP流封装协议与Flickr API通信
 *
 * 不要误以为这是普通的网页URL，file_get_contents()函数的字符串参数其实是一个流标识符。http协议会让PHP使用HTTP流封装协议。在这个参数中，http之后是流的目标。流的目标之所以看起来像是普通的网页URL，是因为HTTP流封装协议就是这样规定的。其他流封装协议可能不是这样。
 *
 */

// $json = file_get_contents('http://api.flickr.com/services/feeds/photos_public.gne?format=json');


/* 隐式使用file://流封装协议 */

$handle = fopen('test.txt', 'rb');
while (feof($handle) !== true) {
    echo fgets($handle);
}
fclose($handle);


/* 显式使用file://流封装协议  */

$handle1 = fopen('file://E:WWW\modernphp\stream\test.txt', 'rb');
while (feof($handle1) !== true) {
    echo fgets($handle1);
}
fclose($handle1);

