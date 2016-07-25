<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
<!--转译元素内容-->
<h1><?= Html::encode($demo1); ?></h1>
<!--过滤元素内容-->
<h1><?= HtmlPurifier::process($demo2['demo']); ?></h1>   

