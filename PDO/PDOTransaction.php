<?php
/**
 * Created by coder meng.
 * User: coder meng
 * Date: 2016/12/13 18:03
 */

/* 使用事务执行数据库查询 */

require 'settings.php';

// PDO连接
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
        $settings['password'],
        array(PDO::ATTR_PERSISTENT => true)
    );
} catch (PDOException $e) {
    // 连接数据库失败
    echo 'Database connection failed';
    exit;
}


try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // 查询语句
    $stmtSubtract = $pdo->prepare('UPDATE users SET amount = amount - :amount WHERE name = :name');
    $stmtAdd = $pdo->prepare('UPDATE users SET amount = amount + :amount WHERE name = :name');

// 开始事务
    $pdo->beginTransaction();

// 从账户1中取钱
    $fromAccount = 'ceshi1';
    $withDrawal = 50;
    $stmtSubtract->bindValue(':name', $fromAccount);
    $stmtSubtract->bindValue(':amount', $withDrawal, PDO::PARAM_INT);
    $stmtSubtract->execute();

// 把钱存入账户2
    $toAccount = 'ceshi2';
    $deposit = 50;
    $stmtAdd->bindValue(':name', $toAccount);
    $stmtAdd->bindValue(':amount', $deposit, PDO::PARAM_INT);
    $stmtAdd->execute();

// 提交事务
    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();
    echo 'Failed: ' . $e->getMessage();
}
