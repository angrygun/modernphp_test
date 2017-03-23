<?php
/**
 * Created by coder meng.
 * User: coder meng
 * Date: 2016/6/2 15:51
 */
$arr = array(1, 2, 3);
$brr = array(3, 4, 5);
function bijiao($arr, $brr)
{
    for ($i = 0; $i < count($arr); $i++) {
        $like = $arr[$i];
        if (in_array($like, $brr)) {
            unset($arr[$i]);
        }
    }
    var_dump($arr);
}

bijiao($arr, $brr);

$crr = array('a' => 30, 'b' => 20, 'c' => 10, 'd' => 60, 'e' => 10);
foreach ($crr as $key => $v) {
    $drr[] = $key;
    $err[] = $v;
}
echo '<hr/>';
var_dump($crr);
for ($i = 0; $i < count($crr) - 1; $i++) {
    for ($j = 0; $j < count($crr) - 1 - $i; $j++) {
        if ($err[$j] > $err[$j + 1]) {
            $tmp = $err[$j];
            $err[$j] = $err[$j + 1];
            $err[$j + 1] = $tmp;
            $tmp1 = $drr[$j];
            $drr[$j] = $drr[$j + 1];
            $drr[$j + 1] = $tmp1;
        }
    }
}
$frr = array_combine($drr, $err);
echo '<hr/>';
var_dump($frr);

function upload($upfile, $savepath, $allowlist)
{
    $savepath = rtrim($savepath, '/') . '/';
    $error = $upfile['error'];
    $mess = array();
    if ($error > 0) {
        switch ($error) {
            case 1:
            case 2:
                $info = '表示上传的文件超出了';
                break;
            case 3:
                $info = '表示文件只被部分上传';
                break;
            case 4:
                $info = '表示没有文件被上传';
                break;
            case 6:
                $info = '表示找不到临时文件';
                break;
            case 7:
                $info = '表示文件写入失败';
                break;
            default:
                $info = '未知错误';
                break;
        }
        return $mess['info'] = $info;
    }
    if (!empty($allowlist)) {
        if (!in_array($upfile['type'], $allowlist)) {
            return $mess['info'] = '上传类型错误' . $upfile['type'];
        }
    }
    $ext = pathinfo($upfile['name'], PATHINFO_EXTENSION);
    do {
        $newname = date('YmdHis') . rand(0, 20000) . '.' . $ext;
    } while (file_exists($savepath . $newname));
    if (is_uploaded_file($upfile['tmp_name'])) {
        if (move_uploaded_file($upfile['tmp_name'], $savepath . $newname)) {
            $mess['up'] = true;
            $mess['info'] = '新文件名' . $newname;
            return $mess;
        } else {
            return $mess['info'] = '存储上传文件失败';
        }
    } else {
        return $mess['info'] = '非法文件';
    }
}

echo '<pre>';
var_dump($_SERVER);


function myfunc($a)
{
    echo $a + 10;
}

$b = 10;
echo "myfunc($b)=" . myfunc($b);

echo '<br/>';

$a = 0;
$b = 'abcd';
if ($a == $b) {
    echo 111111;
}

echo '<br/>';
$ar = array('0' => 'first', 'a' => 'second');
foreach ($ar as $k => $v) {
    $vrr[] = $k;
}
$preg = '/\d+/';
if (preg_grep($preg, $ar)) {
    echo 222222222;
} else {
    echo 44444444;
}
$arr1 = array(
    "0" => array('fid' => 1, 'tid' => 1, 'name' => 'name1'),
    "1" => array('fid' => 1, 'tid' => 2, 'name' => 'name2'),
    "2" => array('fid' => 1, 'tid' => 3, 'name' => 'name3'),
    "3" => array('fid' => 1, 'tid' => 4, 'name' => 'name4')
);
$arr2 = array(
    "0" => array(
        "0" => array('tid' => 1, 'name' => 'name1'),
        "1" => array('tid' => 2, 'name' => 'name2'),
        "2" => array('tid' => 3, 'name' => 'name3'),
    ),
    "1" => array(
        "3" => array('tid' => 4, 'name' => 'name4')
    )
);
echo '<br/>';
foreach ($arr1 as $k => $v) {
    unset($v['fid']);
    if ($k < 3) {
        $arr3[0][] = $v;
    } elseif ($k == 3) {
        $arr3[1][$k] = $v;
    }
}
var_dump($arr3);
$str = "http://game.weibo.com/hoe/rec";
preg_match("/^(http:\/\/)?([^\/]+)/i", $str, $matches);
var_dump($matches);
$i = 97;
$a = ($i++) + (++$i) + $i;
$b = (--$i) + ($i--) + $i + 6;
echo "$i,$a,$b";

/*
 * 冒泡排序
 */

$arr = array(10, 33, 20, -10, -1, 55);

function bubbleSort($arr)
{
    $count = count($arr);
    for ($i = 1; $i < $count; $i++) {
        for ($j = 0; $j < $count - $i; $j++) {
            if ($arr[$j] > $arr[$j + 1]) {
                $tmp = $arr[$j + 1];
                $arr[$j + 1] = $arr[$j];
                $arr[$j] = $tmp;
            }
        }
    }
    return $arr;
}

var_dump(bubbleSort($arr));

/*
 * 快速排序
 * */

function quickSort($arr)
{
    $left_array = [];
    $right_array = [];
    if (count($arr) <= 1) {
        return $arr;
    }
    $tmp = $arr[0];
    for ($i = 1; $i < count($arr); $i++) {
        if ($arr[$i] > $tmp) {
            $right_array[] = $arr[$i];
        } else {
            $left_array[] = $arr[$i];
        }
    }
    $left_array = quickSort($left_array);
    $right_array = quickSort($right_array);
    $arr = array_merge($left_array, array($tmp), $right_array);
    return $arr;
}

echo '<hr/>';
var_dump(quickSort($arr));

/*
 * 选择排序
 * */

function selectSort($arr)
{
    $count = count($arr);
    for ($i = 0; $i < $count - 1; $i++) {
        $p = $i;
        for ($j = $i + 1; $j < $count; $j++) {
            if ($arr[$p] > $arr[$j]) {
                $p = $j;
            }
        }
        if ($p != $i) {
            $tmp = $arr[$p];
            $arr[$p] = $arr[$i];
            $arr[$i] = $tmp;
        }
    }
    return $arr;
}

echo '<hr/>';
var_dump(selectSort($arr));

/*
 * 插入排序
 * */

function insertSort($arr)
{
    $count = count($arr);
    for ($i = 1; $i < $count; $i++) {
        $tmp = $arr[$i];
        for ($j = $i - 1; $j >= 0; $j--) {
            if ($tmp < $arr[$j]) {
                $arr[$j + 1] = $arr[$j];
                $arr[$j] = $tmp;
            } else {
                break;
            }
        }
    }
    return $arr;
}

echo '<hr/>';
var_dump(insertSort($arr));