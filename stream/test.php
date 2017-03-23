<?php

/* 接收json格式数据 */
echo json_decode(file_get_contents('php://input'), true)['username'];

/* 接收正常post数据 */
# var_dump($_POST['username']);
