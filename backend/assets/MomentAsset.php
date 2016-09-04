<?php
/**
 * @author: timelesszhuang [<https://github.com/timelesszhuang>]
 */
namespace backend\assets;

use yii\web\AssetBundle;

class MomentAsset extends AssetBundle{

    public $sourcePath='@vendor/moment';

    public $css = [

    ];

    public $js = [
        'moment/min/moment.min.js',
        'moment/locale/zh-cn.js'
    ];
    public $depends = [

    ];

}