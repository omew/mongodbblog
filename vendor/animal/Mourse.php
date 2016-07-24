<?php

namespace vendor\animal;

/**
 * 老鼠老鼠类
 * @author timeless
 */
class Mourse {

    /**
     * 触发之后的操作
     * @access public
     * @param yii\base\event $c_e 事件传递参数
     */
    public function run($c_e) {
        echo '老鼠：卧槽，有猫赶紧跑吧,猫还说：' . $c_e->message;
    }

}
