<?php
/**
 * @author: mojifan [<https://github.com/mojifan>]
 */
namespace common\models;

use yii\helpers\Html;

class Category extends Meta
{

    const TYPE = 'category';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'slug'], 'checkSlugName', 'skipOnEmpty' => false],
            [['name', 'slug'], 'checkNameExist', 'skipOnEmpty' => false],
            [['name', 'slug', 'description'], 'string', 'max' => 200],
            [['description'], 'filter', 'filter' => function ($value) {
                return Html::encode($value);
            }],
            [['parent'], 'filter', 'filter' => function ($value) {
                $value = intval($value);
                if ($value != 0) {
                    //如果分类没有查询到的话
                    $parent = self::find()->where(['id' => $value])->one();
                    return $parent ? $value : 0;
                }
                return 0;
            }],
        ];
    }
    

}