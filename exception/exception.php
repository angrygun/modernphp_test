<?php
/**
 * Created by coder meng.
 * User: coder meng
 * Date: 2016/12/19 18:57
 */

/*
 * Exception对象与其他任何PHP对象一样，使用new关键字实例化。
 *
 * Exception对象有两个主要的属性：一个是消息，另一个是数字代码。
 * 消息用于描述出现的问题；数字代码是可选的，用于为指定的异常提供上下文。
 *
 * */

// 实例化Exception对象时可以像下面这样设定消息和可选的数字代码

$exception = new Exception('Danger, Will Robinson!', 100);

echo $code = $exception->getCode(), PHP_EOL;
echo $message = $exception->getMessage();

/*
 * 抛出异常
 *
 * 抛出异常后代码会立即停止执行，后续的PHP代码都不会运行。
 *
 * 抛出异常的方式是使用throw关键字，后面跟着要抛出的Exception示例：
 *      throw new Exception('Something went wrong. Time for lunch!');
 *
 *
 * 捕获异常
 *
 * 拦截并处理潜在异常的方式是，把可能抛出异常的代码放在try/catch块中，catch块会捕获这个异常，然后显示一个友好的错误信息，而不是丑陋的堆栈跟踪。
 * */

// 捕获抛出的异常

try {
    $pdo = new PDO('mysql:host=wrong_host;dbname=wrong_name');
} catch (PDOException $e) {
    // 获取异常的属性，以便输出信息
    $code = $e->getCode();
    $message = $e->getMessage();

    // 给用户显示一个友好的消息
    echo 'Something went wrong. Check back soon,please.';
    // exit;
}

// 捕获抛出的多个异常
try {
    throw new Exception('Not a PDO exception');
    $pdo = new PDO('mysql:host=wrong_host;dbname=wrong_name');
} catch (PDOException $e) {
    // 处理PDOException异常
    echo 'Caught PDO exception';
} catch (Exception $e) {
    // 处理所有其他类型的异常
    echo 'Caught generic exception';
} finally {
    // 这里的代码始终都会运行
    echo 'Always do this';
}

/*
 * 异常处理程序
 *
 * PHP允许我们注册一个全局异常处理程序，捕获所有未被捕获的异常。
 * 我们一定要设置一个全局异常处理程序。
 * 异常处理程序是最后的安全保障，如果没有成功捕获并处理异常，通过这个措施可以给PHP应用的用户显示合适的错误消息。
 *
 * 异常处理程序是任何可调用的代码。一般使用匿名函数，也可以使用类方法。
 * 不管选择使用什么，异常处理程序都必须接收一个类型为Exception的参数。
 *
 * 异常处理程序使用set_exception_handler()函数注册
 * */


// 设置全局异常处理程序

// 注册异常处理程序
set_exception_handler(function (Exception $e) {
    // 处理并记录异常

});

// 我们编写的其他代码......

// 还原成之前的异常处理程序
restore_exception_handler();