<?php
/**
 * Created by coder meng.
 * User: coder meng
 * Date: 2016/12/13 17:35
 */

require 'settings.php';

/* 执行数据库查询时没使用事务 */

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
} catch (PDOException $e) {
    // 连接数据库失败
    echo 'Database connection failed';
    exit;
}

// 查询语句
$stmtSubtract = $pdo->prepare('UPDATE users SET amount = amount - :amount WHERE name = :name');
$stmtAdd = $pdo->prepare('UPDATE users SET amount = amount + :amount WHERE name = :name');

// 从账户1中取钱
$fromAccount = 'ceshi1';
$withDrawal = 50;
$stmtSubtract->bindParam(':name', $fromAccount);
$stmtSubtract->bindParam(':amount', $withDrawal, PDO::PARAM_INT);
$stmtSubtract->execute();

// 把钱存入账户2
$toAccount = 'ceshi2';
$deposit = 50;
$stmtAdd->bindParam(':name', $toAccount);
$stmtAdd->bindParam(':amount', $deposit, PDO::PARAM_INT);
$stmtAdd->execute();