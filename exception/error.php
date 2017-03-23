<?php

/*
 * 错误处理程序
 *
 * 与异常处理程序一样，错误处理程序可以是任何可调用的代码（例如函数或类方法）
 * 我们要在错误处理程序中调用die()或exit()函数。如果再错误处理程序中不手动终止执行PHP脚本，PHP脚本会从出错的地方继续想下执行。
 *
 * 注册全局错误处理程序的方式是使用set_error_handler()函数。
 *
 * 使用自定义的错误处理程序一定要知道一个重要的注意事项：PHP会把所有错误都交给错误处理程序处理，甚至包括错误报告设置中排除的错误。因此我们要检查每个错误代码（第一个参数），然后做适当的处理。我们可以通过set_error_handler()函数的第二个参数，让错误处理程序只处理一部分错误类型。这个参数的值是使用E_*常量组合的位掩码（例如E_ALL | E_STRICT）
 *
 * 注意：并不是所有错误都能转换成异常！
 *      不能转换成异常的错误有：E_ERROR、E_PARSE、E_CORE_ERROR、E_CORE_WARNING、E_COMPILE_ERROR、E_COMPILE_WARNING和大多数E_STRICT错误。
 * */

// 设置全局错误处理程序

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    //var_dump(error_reporting());var_dump($errno);
    if (!(error_reporting() & $errno)) {
        // error_reporting指令没有设置这个错误，所以将其忽略
        return;
    }

    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
});

// 我们编写的其他代码......

set_exception_handler(function (Exception $e) {
    // 处理并记录异常
    echo $e->getLine() . '----' . $e->getMessage();
    die;
});

function  scale_by_log ( $vect ,  $scale )
{
    if (! is_numeric ( $scale ) ||  $scale  <=  0 ) {
        trigger_error ( "log(x) for x <= 0 is undefined, you used: scale =  $scale " ,  E_USER_ERROR );
    }

    if (! is_array ( $vect )) {
        trigger_error ( "Incorrect input vector, array of values expected" ,  E_USER_WARNING );
        return  null ;
    }

    $temp  = array();
    foreach( $vect  as  $pos  =>  $value ) {
        if (! is_numeric ( $value )) {
            trigger_error ( "Value at position  $pos  is not a number, using 0 (zero)" ,  E_USER_NOTICE );
            $value  =  0 ;
        }
        $temp [ $pos ] =  log ( $scale ) *  $value ;
    }

    return  $temp ;
}

// set to the user defined error handler
// $old_error_handler  =  set_error_handler ( "myErrorHandler" );

// trigger some errors, first define a mixed array with a non-numeric item
echo  "vector a\n" ;
$a  = array( 2 ,  3 ,  "foo" ,  5.5 ,  43.3 ,  21.11 );
print_r ( $a );

echo '<hr/>';
// now generate second array
echo  "----\nvector b - a notice (b = log(PI) * a)\n" ;
/* Value at position $pos is not a number, using 0 (zero) */
$b  =  scale_by_log ( $a ,  M_PI );
print_r ( $b );

echo '<hr/>';
// this is trouble, we pass a string instead of an array
echo  "----\nvector c - a warning\n" ;
/* Incorrect input vector, array of values expected */
$c  =  scale_by_log ( "not array" ,  2.3 );
var_dump ( $c );  // NULL

echo '<hr/>';
// this is a critical error, log of zero or negative number is undefined
echo  "----\nvector d - fatal error\n" ;
/* log(x) for x <= 0 is undefined, you used: scale = $scale" */
$d  =  scale_by_log ( $a , - 2.5 );
var_dump ( $d );  // Never reached

// 还原成之前的错误处理程序
restore_error_handler();

// 还原成之前的异常处理程序
restore_exception_handler();
