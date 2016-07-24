<?php

/**
 *猫类
 * @author timeless
 */

namespace vendor\animal;

use yii\base\Component;
use yii\base\Event;

/**
 * 事件处理器就获得了以下有关事件的信息：
[[yii\base\Event::name|event name]]：事件名
[[yii\base\Event::sender|event sender]]：调用 trigger() 方法的对象
[[yii\base\Event::data|custom data]]：附加事件处理器时传入的数据，默认为空，后文详述
 */
class catEvent extends Event {

    public $message;

}

//继承component 之后会可以执行事件触发
class Cat extends Component {

    /**
     * 触发器测试
     * @access public
     */
    public function shout() {
        echo '<br>喵喵喵<br>';
        //包含第二个参数
        $c_e = new catEvent();
        $c_e->message = '我现在很饿，想要吃东西';
        $this->trigger('miao', $c_e);
    }

}
