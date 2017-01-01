<?php
/**
 * Created by coder meng.
 * User: coder meng
 * Date: 2016/12/9 11:15
 */

/*创建简单的闭包*/
$closure = function ($name) {
    return sprintf('Hello %s', $name);
};
echo $closure('Josh');

echo '<hr/>';

/*在array_map()函数中使用闭包*/

$numberPlusOne = array_map(function ($number) {
    return $number + 1;
}, [1, 2, 3]);
print_r($numberPlusOne);

echo '<hr/>';

/*实现具名回调*/
function incrementNumber($number)
{
    return $number + 1;
}

//使用具名回调
$numberPlusOne1 = array_map('incrementNumber', [1, 2, 3]);
print_r($numberPlusOne1);

echo '<hr/>';

/*使用use关键字附加闭包状态*/

function enclosePerson($name)
{
    return function ($doCommand) use ($name) {
        return sprintf('%s, %s', $name, $doCommand);
    };
}

//把字符串“Clay”封装在闭包中
$clay = enclosePerson('Clay');

//传入参数，调用闭包
echo $clay('get me sweet tea!');

echo '<hr/>';

/*
 * PHP框架经常使用bindTo()方法把路由URL映射到匿名回调函数上。框架会把匿名函数绑定到应用对象上，这么做可以在这个匿名函数中使用$this关键字引用重要的应用对象。
 *
 * */

//使用bindTo()方法附加闭包的状态

class App
{
    protected $routes = array();
    protected $responseStatus = '200 OK';
    protected $responseContentType = 'text/html';
    protected $responseBody = 'Hello world';

    public function addRoute($routePath, $routeCallback)
    {
        $this->routes[$routePath] = $routeCallback->bindTo($this, __CLASS__);
    }

    public function dispatch($currentPath)
    {
        foreach ($this->routes as $routePath => $callback) {
            if ($routePath === $currentPath) {
                $callback();
            }
        }

        header('HTTP/1.1 ' . $this->responseStatus);
        header('Content-type: ' . $this->responseContentType);
        header('Content-length: ' . mb_strlen($this->responseBody));
        echo $this->responseBody;
    }
}

$app = new App();
$app->addRoute('/Users/josh', function () {
    $this->responseContentType = 'application/json;charset=utf8';
    $this->responseBody = '{"name":"Josh"}';
});
$app->dispatch('/Users/josh');