<?php

/**
 * @author timeless
 */

namespace vendor\demo;

use app\behaviors\behaviorsdemo;
use yii\base\Component;

class Dog extends Component {

    //让类拥有处理事件 转发事件的能力  接受行为方法的能力  类的混合  静态混合
    public function behaviors() {
        //行为中的方法注入给 Dog
        return [behaviorsdemo::className()];
    }
    
    public function demofunction() {
        echo 'demofunction这个是组件自己的函数<br/>';
    }

}
