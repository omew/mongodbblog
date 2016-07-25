<?php

namespace backend\assets;

use yii\web\AssetBundle;

class LoginAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/login.css',
        'public/bootstrap/css/bootstrap.min.css'
    ];
    public $js = [
        'js/jquery-1.12.4.min.js',
        'public/bootstrap/js/bootstrap.min.js'
    ];
    public $depends = [
        //添加yii的js 库
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];

    //定义按需加载JS方法，注意加载顺序在最后  
    public static function addScript($view, $jsfile) {
        $view->registerJsFile($jsfile, [AppAsset::className(), 'depends' => 'app\assets\AppAsset']);
    }

    //定义按需加载css方法，注意加载顺序在最后  
    public static function addCss($view, $cssfile) {
        $view->registerCssFile($cssfile, [AppAsset::className(), 'depends' => 'app\assets\AppAsset']);
    }
}
