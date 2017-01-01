<?php
/**
 * Created by coder meng.
 * User: coder meng
 * Date: 2016/12/19 18:18
 */

/* 自定义流过滤器
 *
 * 其实大多数情况下都要使用自定义的流过滤器。
 * 自定义的流过滤器是个PHP类，扩展内置的php_user_filter类
 * 这个类必须实现filter()、onCreate()和onClose()方法，而且必须使用stream_filter_register()函数注册自定义的流过滤器
 *
 *
 * PHP流会把数据分成按次序排列的桶，一个桶中盛放的流数据量是固定的（例如4096字节）。如果还用管道比喻，就是把水放在一个个水桶中，顺着管道从出发地漂流到目的地，再漂流的过程中会经过流过滤器。流过滤器一次能接受并处理一个或多个桶，一定时间内过滤器接收到的桶叫做桶队列。
 *
 * 桶队列中的每个桶对象都有两个公开属性：data和datalen。这两个属性的值分别是桶中的内容和内容的长度。
*/

// 自定义的DirtyWordsFilter流过滤器

class DirtyWordsFilter extends php_user_filter
{
    /*
     * @param resource $in    流来的桶队列
     * @param resource $out   流走的桶队列
     * @param int   $consumed 处理的字节数
     * @param bool  $closing  是流中最后一个桶队列吗？
     * */

    public function filter($in, $out, &$consumed, $closing)
    {
        $words = array('grime', 'dirt', 'grease');
        $wordData = array();
        foreach ($words as $word) {
            $replacement = array_fill(0, mb_strlen($word), '*');
            $wordData[$word] = implode('', $replacement);
        }
        $bad = array_keys($wordData);
        $good = array_values($wordData);

        // 迭代流来的桶队列中的每个桶
        while ($bucket = stream_bucket_make_writeable($in)) {
            // 审查桶数据中的脏字
            $bucket->data = str_replace($bad, $good, $bucket->data);

            // 增加已处理的数量
            $consumed +=$bucket->datalen;

            // 把桶放入流向下游的队列中
            stream_bucket_append($out, $bucket);
        }

        return PSFS_PASS_ON;
    }
}

stream_filter_register('dirty_words_filter', 'DirtyWordsFilter');

// 使用DirtyWordsFilter流过滤器

$handle = fopen('test.txt', 'rb');
stream_filter_append($handle, 'dirty_words_filter');
while (feof($handle) !== true) {
    echo fgets($handle);// 输出审查后的文本
}
fclose($handle);