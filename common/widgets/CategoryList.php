<?php
/**
 * @author: timelesszhuang [<https://github.com/timelesszhuang>]
 */

namespace common\widgets;

use common\components\CategoryTree;
use yii;
use yii\helpers\Html;

class CategoryList extends yii\base\Widget{

    private $_htmlStr;

    public $options;

    public function init(){

        parent::init();

        $categories=CategoryTree::getInstance()->getAllCategories();

        $this->_htmlStr='<ul>';

        if(!empty($categories)){
            foreach($categories as $v){

                $this->_htmlStr.='<li>';
                $this->_htmlStr.=str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$v['depth']-1).Html::a($v['name'].'('.$v['count'].')',['site/category','id'=>$v['id']],$this->options);
                $this->_htmlStr.='</li>';

            }
        }


        $this->_htmlStr.='</ul>';

    }

    public function run(){

        return $this->_htmlStr;


    }
}