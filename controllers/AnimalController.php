<?php

namespace app\controllers;

use yii\web\Controller;
use vendor\animal\Cat;
use vendor\animal\Mourse;
use vendor\demo\Dog;
use app\behaviors\behaviorsdemo;
use yii\base\Event;
use yii\base\Application;

/**
 * Description of AnimalController
 *
 * @author timeless
 */
class AnimalController extends Controller {

    /**
     * 该函数主要用户测试 事件触发 包含事件触发的参数值传递
     * @access public
     */
    public function actionIndex() {
        $cat = new Cat();
        $cat1 = new Cat();
        $mourse = new Mourse();
        //监听事件
        $cat->on('miao', [$mourse, 'run']);
        //其他的也可以监听 该事件
        //也可以取消监听 
        $cat->off('miao', [$mourse, 'run']);
        $cat->shout();
        //也可以使用 yii\base\Event 绑定  类级别的绑定事件  只要是cat 的实例都会监听 miao 事件
        Event::on(Cat::className(), 'miao', [$mourse, 'run']);
        $cat->shout();
        $cat1->shout();
        //Event::on也可以绑定匿名函数
        Event::on(Cat::className(), 'miao', function($ev) {
            echo '<br>我是自定义的匿名函数，不用跑哈哈哈哈哈。<br>' . $ev->message;
        });
        $cat->shout();
    }

    /**
     * 测试系统运行注册的事件
     * @access public
     */
    public function actionTestappevent() {
        Event::on(Application::className(), Application::EVENT_AFTER_REQUEST, function() {
            echo '请求之后';
        });
        echo '<br>hellow,正在执行，执行完成之后才会触发事件<br>';
    }

    /**
     * 测试行为相关操作 类的混合静态混合  可以静态或动态地附加行为到yii\base\Component   类混合静态附加  对象混合是动态附加
     * @access public
     */
    public function actionBehaviormixindemo() {
        $dog = new Dog();
        //dog本身的方法  
        $dog->demofunction();
        //行为附加到component 上的行为的函数   附加到的行为的函数以及属性 只能是public 才能被附加到组件类   因为 行为类跟组件类 之间的关系不是父子类关系
        $dog->demo();
        echo $dog->demo1;
        $dog->trigger('wang');
    }

    /**
     * 对象混合  对象混合是动态附加
     * @access public
     */
    public function actionBehaviormixindemo1() {
        $beha = new behaviorsdemo();
        $dog = new Dog();
        $dog->attachBehavior('beha1', $beha);
        //demo操作是 行为中的函数
        $dog->demo();
        $dog->detachBehavior('beha1');
        $dog->demo();
        $dog->trigger('wang');
    }

}
