<?php

/* php://流封装协议 */

// php://stdin
# 这是个只读流，其中的数据来自标准输入。例如，PHP脚本可以使用这个流接受命令行传入脚本的信息。
// php://stdout
# 这个PHP流的作用是把数据写入当前的输出缓冲区。这个流只能写，无法读或寻址。
// php://memory
# 这个PHP流的作用是从系统内存中读取数据，或者把数据写入系统内存。这个PHP流的缺点是，可用内存是有限的。使用php://temp流更安全。
// php://temp
# 这个php流的作用和php://memory类似，不过，没有可用内存时，PHP会把数据写入临时文件。


/* 流上下文
 *
 * 有些PHP流能接受一系列可选的参数，这些参数叫流上下文，用于定制流的行为。不同的流封装协议使用的上下文参数有所不同。流上下文使用stream_context_create()函数创建。这个函数返回的上下文对象可以传入大多数文件系统和流函数。
 *
 */

// 流上下文
# 你知道可以使用file_get_contents()函数发送HTTP POST请求吗？


/* post json格式数据 */
$requestBody = '{"username":"josh"}';
$context = stream_context_create(array(
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-Type: application/json;charset=utf-8;\r\n' .
            'Content-Length: ' . mb_strlen($requestBody),
        'content' => $requestBody
    )
));
$response = file_get_contents('http://127.0.0.1/modernphp/stream/test.php', false, $context);
echo $response;


/* post 正常格式数据 */
/*$requestBody = ["username"=>"josh"];
$requestBody1 = http_build_query($requestBody);
$context = stream_context_create(array(
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-Type:  application/x-www-form-urlencoded;charset=utf-8;\r\n' .
            'Content-Length: ' . mb_strlen($requestBody1),
        'content' => $requestBody1
    )
));
$response = file_get_contents('http://localhost/modernphp/stream/test.php', false, $context);
echo $response;*/