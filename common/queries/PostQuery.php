<?php

/**
 * @author: timelesszhuang [<https://github.com/timelesszhuang>]
 */

namespace common\queries;

use common\models\Post;
use yii\mongodb\ActiveQuery;

class PostQuery extends ActiveQuery
{

    public function init()
    {
        parent::init();
    }

    /**
     * 选择字段  不包text 字段
     * @access public
     */
    public function selectNoText()
    {
        $columns = (new Post())->attributes();
        $key = array_search('text', $columns);
        if ($key !== false) {
            unset($columns[$key]);
        }
        $this->select(array_values($columns));
        return $this;
    }

    public function published()
    {
        $this->andWhere(['status' => Post::STATUS_PUBLISH]);
        return $this;
    }

    public function recentPublished($limit = 10)
    {
        $this->andWhere(['status' => Post::STATUS_PUBLISH]);
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
