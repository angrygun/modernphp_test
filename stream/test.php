<?php
/**
 * Created by coder meng.
 * User: coder meng
 * Date: 2016/12/14 16:17
 */

/* 接收json格式数据 */
echo json_decode(file_get_contents('php://input'), true)['username'];

/* 接收正常post数据 */
# var_dump($_POST['username']);
