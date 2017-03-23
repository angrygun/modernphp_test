<?php

/* 登录用户的脚本 */

session_start();
try {
    // 从请求主体中获取电子邮件地址
    $email = filter_input(INPUT_POST, 'email');

    // 从请求主体中获取密码
    $password = filter_input(INPUT_POST, 'password');

    // 使用电子邮件查找账户（注意，这是虚构的代码）
    // $user = User::findByEmail($email);

    // 验证密码和账户的密码哈希值是否匹配
    if (password_verify($password, '$2y$12$gb6Zn9GyX/j5x0bg05n8wOxgSbXJQxPBu2pADHVwjvRM1tUMLZMSC') === false) {
        throw new Exception('Invalid password');
    }

    // 如果需要，重新计算密码的哈希值（参加下面的说明）
    $currentHashAlgorithm = PASSWORD_DEFAULT;
    $currentHashOptions = array('cost' => 15);
    $passwordNeedsRehash = password_needs_rehash(
        '$2y$12$gb6Zn9GyX/j5x0bg05n8wOxgSbXJQxPBu2pADHVwjvRM1tUMLZMSC',
        $currentHashAlgorithm,
        $currentHashOptions
    );
    if ($passwordNeedsRehash === true) {
        // 保存新计算得到的密码哈希值（注意，这是虚构的代码）
        $user_password_hash = password_hash(
            $password,
            $currentHashAlgorithm,
            $currentHashOptions
        );
        echo $user_password_hash;
        //$user->save();
    }

    // 把登录状态保存到会话中
    $_SESSION['user_logged_in'] = 'yes';
    $_SESSION['user_email'] = $email;

    // 重定向到个人资料页面
    header('HTTP/1.1 302 Redirect');
    // header('Location: /user-prifile.php');
} catch (Exception $e) {
    header('HTTP/1.1 401 Unauthorized');
    echo $e->getMessage();
}