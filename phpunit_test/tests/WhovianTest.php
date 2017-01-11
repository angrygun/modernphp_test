<?php
/**
 * Created by coder meng.
 * User: coder meng
 * Date: 2017/1/9 17:33
 */
require dirname(__DIR__) . '/src/Whovian.php';

class WhovianTest extends PHPUnit_Framework_TestCase
{
    // 测试__construct()方法
    public function testSetsDoctorWithConstructor()
    {
        $whovian = new Whovian('Peter Capaldi');
        $this->assertAttributeEquals('Peter Capaldi', 'favoriteDoctor', $whovian);
    }

    // 测试say()方法
    public function testSaysDoctorName()
    {
        $whovian = new Whovian('David Tennant');
        $this->assertEquals('The best doctor is David Tennant', $whovian->say());
    }

    // 测试表示认同的respongTo()方法
    public function testRespondToInAgreement()
    {
        $whovian = new Whovian('David Tennant');

        $opinion = 'David Tennant is the best doctor, period';
        $this->assertEquals('I agree!', $whovian->respondTo($opinion));
    }

    /*
     * @expectedException Exception
     * */
    public function testRespondToInDisagreement()
    {
        $whovian = new Whovian('David Tennant');

        $opinion = 'No way. Matt Smith was awesome!';
        $whovian->respondTo($opinion);
    }
}