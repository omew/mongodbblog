<?php

namespace common\models;

use common\helpers\StringHelper;
use common\queries\MetaQuery;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%metas}}".
 *
 * @property integer $mid
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $description
 * @property integer $count
 * @property integer $order
 * @property integer $parent
 * @property integer id
 */
abstract class Meta extends \yii\mongodb\ActiveRecord
{
    //const TYPE_CATEGORY='category';
    //const TYPE_TAG='tag';

    const TYPE = '';

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'meta';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'name' => '名称',
            'slug' => '缩略名',
            'type' => '类型',
            'description' => '描述',
            'count' => '文章数',
            'order' => '排序',
            'parent' => '父级',
        ];
    }

    /**
     *该必须要是实现
     */
    public function attributes()
    {
        return ['_id', 'id', 'name', 'slug', 'type', 'description', 'count', 'order', 'parent'];
    }

    /**
     * 检测生成缩略名
     * @param $attribute
     * @param $params
     */
    public function checkSlugName($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $name = StringHelper::generateCleanStr($this->$attribute);
            if (($attribute == 'name' && empty($name)) || ($attribute == 'slug' && !empty($this->slug) && empty($name))) {
                $this->addError($attribute, $this->getAttributeLabel($attribute) . '全部为非法字符,无法转换');
            }
            if ($attribute == 'slug' && empty($this->slug)) {
                $this->$attribute = $this->name;
            } else {
                $this->$attribute = $name;
            }
        }
    }

    public function checkNameExist($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $model = self::findOne([$attribute => $this->$attribute, 'type' => $this->type]);
            if ($this->isNewRecord) {
                if ($model != null) {
                    $this->addError($attribute, $this->getAttributeLabel($attribute) . '已经存在');
                }
            } else {
                if ($model != null && $model->id != $this->id) {
                    $this->addError($attribute, $this->getAttributeLabel($attribute) . '已经存在');
                }
            }
        }
    }

    public static function find()
    {
        // 使用到了
        /* Object 类的初始化函数
        public function __construct($config = [])
        {
            if (!empty($config)) {
                Yii::configure($this, $config);
            }
            $this->init();
        }
        */
        return new MetaQuery(get_called_class(), ['metaType' => static::TYPE]);
    }

    /**
     *
     */
    public function getPosts($isPublished = true)
    {
        $query = $this->hasMany(Post::className(), ['cid' => 'cid'])->with('categories')->with('tags')->with('author')->orderByCid();
        if ($isPublished) {
            $query = $query->published();
        }
        return $query->viaTable(Relationship::tableName(), ['mid' => 'mid']);
    }

    /**
     *
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                //获取自增的主键
                $this->get_autoincrement_id();
                $this->type = static::TYPE;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取自增主键值
     * @access public
     */
    public function get_autoincrement_id()
    {
        $collection = Yii::$app->mongodb->getCollection('counters');
        $id_arr = $collection->findAndModify(['_id' => 'meta_id'], ['$inc' => ['count' => 1]], ['fields' => ['count' => 1, '_id' => 0]]);
        $this->id = $id_arr['count'];
    }


    /**
     * 更新 标签的 数量 还有文章数组的 id name 数组
     * @access public
     * @param $id
     * @param $postId    文章的id
     * @param $postName  文章的name
     * @param $flag     文章的flag 表示是要 删除 还是 新增
     * @return bool
     */
    public static function updateCategoryTagCountIdname($id, $postId, $postName, $flag)
    {
        $id = intval($id);
        $postId = intval($postId);
        if ($id === 0) {
            return;
        }
        $collection = Yii::$app->mongodb->getCollection(self::collectionName());
        if ($flag === 'add') {
            //添加字段 count+1 id 添加字段  post  数组中添加字段
            $collection->update(
                ['id' => $id],
                ['$inc' => ['count' => 1], '$push' => ['post' => ['id' => $postId, 'name' => $postName]]],
                ['upsert' => false, 'multi' => false]
            );
        } else {
            //数量减少1
            $collection->update(
                ['id' => $id],
                ['$inc' => ['count' => -1]],
                ['upsert' => false, 'multi' => false]
            );
            //这个查询还是费了一点劲   难点在 更新二维数组中的值
            $collection->update(
                ['id' => $id],
                ['$pull' => ['post' => ['id' => $postId]]],
                ['upsert' => false, 'multi' => false]
            );
        }
        return true;
    }


}
