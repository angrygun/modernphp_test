<?php

/*
 * PHP-FPM（PHP FastCGI Process Manager的简称，意思是“PHP FastCGI进程管理器”）是用于管理PHP进程池的软件，用于接收和处理来自Web服务器（例如nginx）的请求。PHP-FPM软件会创建一个主进程（通常以草错系统中根用户的身份运行），控制何时以及如何把HTTP请求转发给一个或多个子进程处理。PHP-FPM主进程还控制什么时候创建（处理Web应用更多的流量）和销毁（子进程运行时间太久或不再需要了）PHP子进程。PHP-FPM进程池中的每个进程存在的时间都比单个HTTP请求长，可以处理10、50、100、500或者更多的HTTP请求。
 *
 *
 *全局配置
 *
 * 在Ubuntu中，PHP-FPM的主配置文件是/etc/php5/fpm/php-fpm.conf；在CentOS中，PHP-FPM的主配置文件是/etc/php-fpm.conf。
 *
 *      下面是PHP-FPM最重要的全局设置，我建议把默认值改为下面列出的值。默认情况下，这两个设置可能被注释掉了，如果需要，去掉注释。这两个设置的作用是，如果在指定的一段时间内有指定个子进程失效了，让PHP-FPM主进程重启。这是PHP-FPM进程的基本安全保障，能解决简单的问题，但是不能解决由拙劣的PHP代码引起的重大问题。
 *      emergency_restart_threshold = 10
 *          在指定的一段时间内，如果失效的PHP-FPM子进程数超过这个值，PHP-FPM主进程就优雅重启。
 *      emergency_restart_interval = 1m
 *          设置emergency_restart_threshold设置采用的时间跨度。
 *
 * 注意：PHP-FPM全局设置的详细信息参见http://php.net/manual/en/install.fpm.configuration.php
 *
 *
 * 配置进程池
 *
 * PHP-FPM配置文件其余的内容是一个名为Pool Definitions的区域。这个区域里的配置用于设置每个PHP-FPM进程池。PHP-FPM进程池中是一系列相关的PHP子进程。通常一个PHP应用由自己的一个PHP-FPM进程池。
 *
 * 在Ubuntu中，Pool Defintions区域只有下面这一行内容：
 *      include=/etc/php5/fpm/pool.d/*.conf
 * CentOS则在PHP-FPM主配置文件的顶部使用下面这行代码引入进程池定义文件：
 *      include=/etc/php-fpm.d/*.conf
 * 这行代码的作用是让PHP-FPM加载/etc/php5/fpm/pool.d/目录（Ubuntu）或/etc/php-fpm.f/目录（CentOS)中的各个进程池定义文件。进入这个目录，应该会看到一个名为www.conf的文件。这是名为www的默认PHP-FPM进程池的配置文件。
 *      注意：每个PHP-FPM进程池的配置文件都是[符号，后跟进程池的名称，然后是]符号。例如，在默认的PHP-FPM进程池的配置文件中，开头是[www]。
 *
 * 各个PHP-FPM进程池都以指定的操作系统用户和用户组的身份运行。我喜欢以单独的非根用户身份运行各个PHP-FPM进程池，这样在命令行中使用top或ps aux命令时便于识别每个PHP应用的PHP-FPM进程池。这是个好习惯，因为每个PHP-FPM进程池中的进程都受相应的操作系统用户和用户组的权限限制在沙盒中。
 *
 * 我们要配置默认的www PHP-FPM进程池，让它以deploy用户和用户组的身份运行。
 *
 *      user = deploy
 *          拥有这个PHP-FPM进程池中子进程的系统用户。要把这个设置的值设为运行PHP应用的非根用户的用户名。
 *      group = deploy
 *          拥有这个PHP-FPM进程池中子进程的系统用户组。要把这个设置的值设为运行PHP应用的非跟用户所属的用户组名。
 *      listen = 127.0.0.1:9000
 *          PHP-FPM进程池坚挺的IP地址和端口号，让PHP-FPM只接受nginx从这里传入的请求，127.0.0.1:9000让指定的PHP-FPM进程池监听从本地端口9000进入的连接。我使用的端口是9000，不过你可以使用任何不需要特殊权限（大于1024）且没被其他系统进程占用的端口号。
 *      listen.allowed_clients = 127.0.0.1
 *          可以向这个PHP-FPM进程池发送请求的IP地址（一个或多个）。为了安全，我把这个设置设为127.0.0.1，即只有当前设备能把请求转发给这个PHP-FPM进程池。默认情况下，这个设置可能被注释掉了，如果需要，去掉这个设置的注释。
 *      pm.max_children = 51
 *          这个设置设定任何时间点PHP-FPM进程池中最多能有多少个进程。这个设置没有绝对正确的值，你应该测试你的PHP应用，确定每个PHP进程需要使用多少内存，然后把这个设置设为设备可用内存能容纳的PHP进程总数。对大多数中小型PHP应用来说，每个PHP进程要使用5~15MB内存（具体用量可能有差异）。假设我们使用的设备为这个PHP-FPM进程池分配了512MB可用内存，那么我们可以把这个设置的值设为（512MB总内存）/（每个进程使用10MB） = 51 个进程。
 *      pm.start_servers = 3
 *          PHP-FPM启动时PHP-FPM进程池中；立即可用的进程数。同样的，这个设置也没有绝对正确的值。对大多数中小型PHP应用来说，我建议设为2或3.这么做是为了先准备好两到三个进程， 等待请求进入，不让PHP应用的头几个HTTP请求等待PHP-FPM初始化进程池中的进程。
 *      pm.min_spare_servers = 2
 *          PHP应用空闲时PHP-FPM进程池中可以存在的进程数量最小值。这个设置的值一般与pm.start_servers设置的值一样，用于确保新进入的HTTP请求无需等待PHP-FPM在进程池中重新初始化进程。
 *      pm.max_spare_servers = 4
 *          PHP应用空闲时PHP-FPM进程池中可以存在的进程数量最大值。这个设置的值一般比pm.start_servers设置的值大一点。用于确保新进入的HTTP请求无需等待PHP-FPM在进程池中重新初始化进程。
 *      pm.max_requests = 1000
 *          回收进程之前，PHP-FPM进程池中各个进程最多能处理的HTTP请求数量。这个设置有助于避免PHP扩展或库因编写拙劣而导致不断泄露内存。我建议设为1000，不过你应该根据应用的需要做调整。
 *      slowlog = /path/to/slowlog.log
 *          这个设置的值是一个日志文件在文件系统中的绝对路径。这个日志文件用于记录处理时间超过n秒的HTTP请求信息，以便找出PHP应用的瓶颈，进行调试。记住，PHP-FPM进程池所属的用户和用户组必须有这个文件的写权限。/path/to/slowlog.log只是示例，请替换成真正的文件路径。
 *      request_slowlog_timeout = 5s
 *          如果当前HTTP请求的处理时间超过指定的值，就把请求的回溯信息写入slowlog设置指定的日志文件。把这个设置的值设为多少，取决于你认为多长时间算久。一开始可以设为5s。
 **/