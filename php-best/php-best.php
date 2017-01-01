<?php
/**
 * Created by coder meng.
 * User: coder meng
 * Date: 2016/12/30 17:14
 */

/*
 * 我们应该使用Apache Bench(http://bit.ly/apache-bench)或Seige(http://www.joedog.org/siege-home/),在类似生产环境的条件下对PHP应用做压力测试，因为最好在把应用部署到生产环境之前却id那个是否又足够的资源可用
 *
 * */


/*Zend OPcache*/


#opcache.memory_consumption = 64
    #为操作码缓存分配的内存量（单位是MB）。分配的内存量应该够保存应用的所有PHP脚本便宜得到的操作码。如果是小型PHP应用，脚本数较少，可以设为较低的值，例如16MB；如果是大型PHP应用，有很多脚本，那就使用较大的值，例如64MB。


#opcache.interned_strings_buffer = 16
    #用来存储驻留字符串（interned string）的内存量（单位是MB）。那么驻留字符串是什么呢？我首先也会想到这个问题。PHP解释器在背后会找到相同字符串的多个实例，把这个字符串保存在内存中，如果再次使用相同的字符串，PHP解释器会使用指针。这么做能节省内存。默认情况下，PHP驻留的字符串会隔离在各个PHP进程中，以便在PHP-FPM进程池中的多个进程之间应用驻留字符串。这样能节省更多内存。这个设置的默认值是4MB，不过我喜欢设为16MB。


#opcache.max_accelerated_files = 4000
    #操作码缓存中最多能存储多少个PHP脚本。这个设置的值可以是200到100000之间的任何数。我使用的是4000.这个值一定要比PHP应用中的文件数量要大。


#opcache.validate_timestamps = 1
    #这个设置的值为1时，经过一段时间后PHP会检查PHP脚本的内容是否变化。检查的时间间隔由opcache.revalidate_frep设置指定。如果这个设置的值为0，PHP不会检查PHP脚本的内容是否变化，我们必须自己手动清除缓存的操作码。我建议在开发环境中设为1，在生产环境中设为0。


#opcache.revalidate_frep = 0
    #设置PHP多久（单位是秒）检查一次PHP脚本的内容是否有变化。缓存的好处时，不用每次请求都重新编译PHP脚本。这个设置用于确定在多长时间内认为操作码缓存是最新的。在这段时间之后，PHP会检查PHP脚本的内容是否有变化。如果有变化，PHP会重新编译脚本，再次缓存。我使用的值是0秒。仅当opcache.validate_timestamps设置的值为1时，这么设置会在每次请求时都重新验证PHP文件。因此在开发环境中，每次请求都会重新验证PHP文件（这是好事）。这个设置在生产环境中没有任何意义，因为生产环境中opcache.validate_timestamps的值始终为0。



/*文件上传*/

#file_uploads = 1
#upload_max_filesize = 10M
#max_file_uploads = 3

//默认情况下，PHP允许在单次请求中上传20个文件，上传的每个文件最大为2MB。你可能不想允许同时上传20个文件，我只允许单次请求上传3个文件。不过，你应该设为对你的应用来说合理的值。

//如果我的PHP应用允许上传文件，通常都会允许上传大于2MB的文件。我把upload_max_filesize设置的值增加到10MB，或许要根据应用的需要设为更高的值。但是别设为太大的值，如果这个值太大，Web服务器会抱怨HTTP请求的主体太大，或者请求会超时。

// 注意：如果需要上传非常大的文件，Web服务器的配置要做相应调整。除了在php.ini文件中设置之外，可能还要调整nginx虚拟主机配置中的client_max_body_size设置。


/*最长执行时间*/

/*
 * php.ini文件中的max_execution_time设置用于设定单个PHP进程在终止之前最长可以运行多少时间。这个设置的默认值时30秒。我们可不想让PHP进程运行30秒，因为我们想让应用运行的特别快（以毫秒计）。我建议把这个设置改为5秒：
 *          max_execution_time = 5
 *
 * 注意：在PHP脚本中可以调用set_time_limit()函数覆盖这个设置。
 *
 *
 * 你可能会问，如果PHP脚本需要运行更长的时间怎么办？答案是，PHP脚本不能长时间运行。PHP运行的时间越长，Web应用的访问者等待响应的时间会越长。如果有长时间运行的任务（例如，调整图像尺寸或生成报告），要在单独的进程中运行。
 *
 *      建议：我会使用PHP中的exec()函数调用bash的at命令。这个命令的作用是派生单独的非阻塞进程，不耽误当前的PHP进程。使用PHP中的exec()函数时，要使用escapeshellarg()函数转义shell参数。
 *
 * 假设我们要生成报告，并把结果制作成PDF文件。这个任务可能要花10分钟才能完成，而我们肯定不想让PHP请求等待10分钟。我们应该单独编写一个PHP文件，加入将其命名为create-report.php，让这个文件运行10分钟，最后生成报告。其实，Web应用只需几毫秒就能派生一个单独的后台进程，然后返回HTTP响应，如下所示：
 *
 *          <?php
 *              exec('echo "create-report.php" | at now');
 *              echo 'Report pending..';
 *
 * create-report.php脚本在单独的后台进程中运行，运行完毕后可以更新数据库，或者通过电子邮件把报告发给收件人。可以看出，我们完全没有理由让长时间运行的任务拖延PHP主脚本，影响用户体验。
 *
 *      建议：如果发现自己派生了很多后台进程，或许最好使用专门的作业队列。PHPResque（https://github.con/chrisboulton/php-resque）是个不错的作业队列管理器，它是鲫鱼GitHub的作业队列管理器Resque（https://github.com/blog/542-introducing-resque）开发的。
 *
 * */



/*处理会话*/


/*
 * PHP默认的会话处理程序会拖慢大型应用，因为这个处理程序会把会话数据存储在硬盘中，需要创建不必要的文件I/O，浪费时间。我们应该把会话数据保存在内存中，例如可以使用Memcached或者Redis。这么做还有个额外好处，以后便于伸缩。如果把会话数据存储在硬盘中，不便于增加额外的服务器。如果把会话数据存储在Memcached或者Redis中央数据存储区里，任何一台分布式PHP-FPM服务器都能访问会话数据。
 *
 *  若想在PHP中访问Memcached存储的数据，要安装连接Memcached的PECL扩展。然后再把下面两行添加到php.ini文件中，把PHP默认的会话存储方式改为Memcached:
 *      session.save_handler = 'memcached'
 *      session.save_path = '127.0.0.1:11211'
 *
 *
 * */


/*缓存输出*/


/*
 * 如果在较少的块中发送更多的数据，而不是在较多的块中发送较少的数据，那么网络的效率会更高。也就是说，在较少的片段中把内容传递给访问者的浏览器，能减少HTTP的请求总数。
 *
 * 因此，我们要让PHP缓冲输出。默认情况下，PHP已经启用了输出缓冲功能（不过没在命令行中启用）。PHP缓冲4096字节的输出之后才会把其中的内容发给Web服务器。下面是我推荐在php.ini中使用的设置：
 *          output_buffering = 4096
 *          implicit_flush = false
 *
 *      建议：如果想修改输出缓冲区的大小，确保使用的值是4（32位系统）或8（64位系统）的倍数。
 *
 * */



/*真实路径缓存*/


/*
 * PHP会缓存应用使用的文件路径，这样每次包含或导入文件时就无需不断搜索包含路径了。这个缓存叫真实路径缓存（realpath cache）。如果运行的是大型PHP文件（例如Drupal和Composer组件等），使用了大量文件，增加PHP真实路径缓存的大小能得到更好的性能。
 *
 * 真实路径缓存的默认大小为16K。这个缓存所需的准确大小不容易确定，不过可以使用一个小技巧。首先，增加真实路径缓存的大小，设为特别大的值，例如256K。然后，在一个PHP脚本的末尾加上print_r(realpath_cache_size());，输出真实路径缓存的真正大小。最后，把真实路径缓存的大小改为这个真正的值。我们可以在php.ini文件中设置真实路径缓存的大小：
 *      realpath_cache_size = 64K
 *
 * */