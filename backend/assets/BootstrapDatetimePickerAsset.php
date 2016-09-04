<?php
/**
 * @author: timelesszhuang [<https://github.com/timelesszhuang>]
 */
namespace backend\assets;

use yii;
use yii\web\AssetBundle;

class BootstrapDatetimePickerAsset extends AssetBundle
{


    /**
     * @app ，必须由开发者在配置文件中提供，一般为配置文件的 dirname(__DIR__) 。 即 digpage.com/frontend 之类的目录。
     * @vendor ，一般定义为 @app/vendor ，高级模板中则定义为 @app/../vendor
     * @bower ，定义为 @vendor/bower
     * @npm ，定义为 @vendor/npm
     * @runtime ，定义为 @app/runtime
     */
    public $sourcePath = '@vendor/eonasdan/bootstrap-datetimepicker';

    public $css = [
        'build/css/bootstrap-datetimepicker.min.css',
    ];
    public $js = [
        'build/js/bootstrap-datetimepicker.min.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'backend\assets\MomentAsset',
    ];

}