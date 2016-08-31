<?php
/**
 * @author: timelesszhuang [<https://github.com/timelesszhuang>]
 */

namespace common\widgets;

use common\components\CategoryTree;
use common\models\Post;
use yii;
use yii\helpers\Html;

class CategoryCheckboxList extends yii\base\Widget
{

    public $category_id;
    private $_inputStr;
    public $options;

    public function init()
    {
        parent::init();
        $this->options['encodeSpaces'] = true;
        $categories = CategoryTree::getInstance()->getAllCategories();
        $this->_inputStr = '<div class="form-group">';
        $this->_inputStr .= Html::label('分类');
        if (!empty($categories)) {
            foreach ($categories as $v) {
                $this->_inputStr .= '<div class="checkbox">';
                $this->_inputStr .= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $v['depth'] - 1) . Html::radio('inputCategory', $v['id'] == $this->category_id, ['label' => $v['name'], 'value' => $v['id']]);
                $this->_inputStr .= '</div>';
            }
        }
        $this->_inputStr .= '</div>';
    }

    public function run()
    {
        return $this->_inputStr;
    }
}