<?php
/**
 * Created by coder meng.
 * User: coder meng
 * Date: 2016/12/13 16:24
 */

require 'settings.php';

try {
    $pdo = new PDO(
        sprintf(
            'mysql:host=%s;dbname=%s;port=%s;charset=%s',
            $settings['host'],
            $settings['dbname'],
            $settings['port'],
            $settings['charset']
        ),
        $settings['username'],
        $settings['password']
    );

    $sql = 'SELECT id, email FROM users WHERE email = :email';
    // $sql = 'SELECT email FROM users WHERE id = :id';
    $statement = $pdo->prepare($sql);

    $email = filter_input(INPUT_GET, 'email');
    // $id =filter_input(INPUT_GET, 'id');
    $statement->bindValue(':email', $email);
    // $statement->bindValue(':id', $id, PDO::PARAM_INT);

    $statement->execute();

    // 迭代结果
    /*while (($result = $statement->fetch(PDO::FETCH_ASSOC)) !== false) {
        echo $result['email'];
    }*/
    /*while (($email = $statement->fetchColumn(1)) !== false) {
        echo $email;
    }*/
    while (($result = $statement->fetchObject()) !== false) {
        echo $result->email;
    }
    echo $pdo->getAttribute(PDO::ATTR_AUTOCOMMIT);
} catch (PDOException $e) {
    // 连接数据库失败
    echo 'Database connection failed';
    exit;
}