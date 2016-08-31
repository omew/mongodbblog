<?php

/**
 * @author: timelesszhuang [<https://github.com/timelesszhuang>]
 */

namespace common\queries;

use common\models\Content;
use common\models\Post;
use yii\db\ActiveQuery;

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
        $columns=(new Post())->attributes();
        $key = array_search('text', $columns);
        if ($key !== false) {
            unset($columns['text']);
        }
        $this->select($columns);
        return $this;
    }

    public function published()
    {
        $this->andWhere(['status' => Content::STATUS_PUBLISH]);
        return $this;
    }

    public function recentPublished($limit = 10)
    {
        $this->andWhere(['status' => Content::STATUS_PUBLISH]);
        $this->limit($limit);
        $this->orderBy(['cid' => SORT_DESC]);
        return $this;
    }

    public function orderById()
    {
        $this->orderBy(['id' => SORT_DESC]);
        return $this;
    }

}
