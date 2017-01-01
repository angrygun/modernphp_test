<?php
/**
 * Created by coder meng.
 * User: coder meng
 * Date: 2016/12/10 16:13
 */

/*
 * 举例说明如何实现项目专用的自动加载器
 *
 * 使用 SPL 注册这个自动加载函数后，遇到下述代码时这个函数
 * 会尝试从 /path/to/project/src/Baz/Qux.php 文件中加载 \Foo\Bar\Baz\Qux 类：
 *
 *      new \Foo\Bar\Baz\Qux;
 *
 * @param string $class 完全限定的类名。
 * @return void
 * */

spl_autoload_register(function ($class) {

    // 这个项目的命名空间前缀
    $prefix = 'Foo\\Bar\\';

    // 这个命名空间前缀对应的基目录
    $base_dir = __DIR__ . '/src/';

    // 参数传入的类使用这个命名空间前缀吗？
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // 不适用，交给注册的下一个自动加载器处理；
        return;
    }

    // 获取去掉前缀后的类名
    $relative_class = substr($class, $len);

    // 把命名空间前缀替换成基目录，
    // 在去掉前缀的类名中，把命名空间分隔符替换成目录分隔符，
    // 然后在后面加上.php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // 如果给文件存在，将其导入
    if (file_exists($file)) {
        require $file;
    }
});