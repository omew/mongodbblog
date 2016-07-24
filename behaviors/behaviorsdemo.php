<?php

//行为 验证测试

namespace app\behaviors;

use yii\base\Behavior;

/**
 * Description of behaviorsdemo
 *
 * @author timeless
 */
class behaviorsdemo extends Behavior {

//    protected $demo1 = '测试组件能不能访问行为中的字段';
    public $demo1 = '测试组件能不能访问行为中的非 public的字段';

    public function demo() {
        echo '这个访问的是行为的函数。';
    }

    /**
     * 行为绑定
     * @access public
     */
    public function events() {
        return[
            'wang' => 'shout',
        ];
    }

    /**
     * 触发某些操作
     */
    public function shout($event) {
        echo '狗叫：：汪汪汪';
    }

}
