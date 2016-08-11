<?php
/**
 * @author: mojifan [<https://github.com/mojifan>]
 */
namespace common\queries;

use yii\mongodb\ActiveQuery;

class MetaQuery extends ActiveQuery
{


    public $metaType;

    public function init()
    {
        parent::init();
        $this->type($this->metaType);
    }

    public function type($type)
    {
        $this->andWhere(['type' => $type]);
        return $this;
    }

    /**
     * orderid 排序
     * 根据id 降序排列
     */
    public function orderByid()
    {
        $this->orderBy(['id' => SORT_DESC]);
        return $this;
    }

    public function orderByCount()
    {
        $this->orderBy(['count' => SORT_DESC]);
        return $this;
    }
}