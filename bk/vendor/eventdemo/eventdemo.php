<?php

/**
 * 组件 该组件测试行为
 * @author timeless
 */
namespace vendor\eventdemo;

use yii\base\Component;
use yii\base\Event;

/**
 * 该类是事件之间传递数据使用
 */
class MessageEvent extends Event {

    public $message;

}

class eventdemo extends Component {

    const EVENT_MESSAGE_SENT = 'messageSent';

    /**
     * 事件触发操作
     * @access public
     * @param mixed $message 要传递给的参数
     */
    public function event_trigger($message) {
        $event = new MessageEvent;
        $event->message = $message;
        $this->trigger(self::EVENT_MESSAGE_SENT, $event);
    }

}
