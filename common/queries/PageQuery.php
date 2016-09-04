<?php
/**
 * Created by PhpStorm.
 * User: timeless
 * Date: 16-9-4
 * Time: 上午10:51
 */

namespace common\queries;


use common\models\Page;
use yii\mongodb\ActiveQuery;

class PageQuery extends ActiveQuery
{

    /**
     *初始化 query
     */
    public function init()
    {
        parent::init();
    }

    /**
     * 选择没有 text 字段
     * @access public
     */
    public function selectNoText()
    {
        $columns = (new Page())->attributes();
        $key = array_search('text', $columns);
        if ($key !== false) {
            unset($columns[$key]);
        }
        $this->select(array_values($columns));
        return $this;
    }

    public function published()
    {
        $this->andWhere(['status' => Page::STATUS_PUBLISH]);
        return $this;
    }

    public function recentPublished($limit = 10)
    {
        $this->andWhere(['status' => Page::STATUS_PUBLISH]);
        $this->limit($limit);
        $this->orderBy(['id' => SORT_DESC]);
        return $this;
    }

    public function orderById()
    {
        $this->orderBy(['id' => SORT_DESC]);
        return $this;
    }


}