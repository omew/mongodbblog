<?php
/**
 * @author: timelesszhuang [<https://github.com/timelesszhuang>]
 */

namespace common\models;

use yii;
use common\helpers\StringHelper;
use common\queries\PostQuery;
use yii\helpers\Html;
USE yii\mongodb\ActiveRecord;


/**
 * @property int|string authorId
 * @property string type
 * @property  modified
 * @property mixed status
 * @property  modified
 * @property  authorName
 * @property  categorie_id
 * @property int _id
 * @property mixed postId
 */
class Post extends ActiveRecord
{
//    use AttachmentOperationTrait;
    const STATUS_PUBLISH = 'publish';
    const STATUS_HIDDEN = 'hidden';
    public $inputCategory;
    public $inputTags;
    public $inputAttachments;
    public $preCategorie;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'slug'], 'string', 'max' => 200],
            [['slug'], 'filter', 'filter' => function ($value) {
                return StringHelper::generateCleanStr($value);
            }],
            [['slug'], 'unique'],
            [['title'], 'default', 'value' => function ($model, $attribute) {
                return '未命名文档';
            }],
            [['title'], 'filter', 'filter' => function ($value) {
                return Html::encode($value);
            }],
            [['order', 'allowComment', 'allowPing', 'allowFeed'], 'filter', 'filter' => function ($value) {
                return intval($value);
            }],
            [['status'], 'filter', 'filter' => function ($value) {
                return in_array($value, [self::STATUS_PUBLISH, self::STATUS_HIDDEN]) ? $value : self::STATUS_PUBLISH;
            }],
            [['created'], 'filter', 'filter' => function ($value) {
                if ($value == '') {
                    return time();
                } else {
                    return strtotime($value);
                }
            }],
            [['text'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'post';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'title' => '标题',
            'slug' => '缩略名',
            'created' => '发布日期',
            'modified' => '修改日期',
            'text' => '内容',
            'order' => '页面顺序',
            'authorId' => '作者',
            'template' => '模板',
            'type' => '类型',
            'status' => '公开度',
            'password' => '密码',
            'commentsNum' => '评论数',
            'allowComment' => '允许评论',
            'allowPing' => '允许被引用',
            'allowFeed' => '允许在聚合中出现',
            'parent' => '所属文章',
        ];
    }

    /**
     *只要是集成mongodb/activerecord的子类都需要实现该方法
     *available attributes
     */
    public function attributes()
    {
        return ['_id', 'id', 'title', 'slug', 'created', 'modified', 'text', 'order', 'authorId', 'authorName', 'template', 'status', 'password', 'category_id', 'category_name', 'tags', 'commentsNum', 'allowComment', 'allowPing', 'allowFeed', 'parent', 'attachments'];
    }


    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->get_autoincrement_id();
                $this->authorId = Yii::$app->user->identity->getId();
                $this->authorName = Yii::$app->user->identity->screenname;
                $this->category_id = intval($this->inputCategory);
                //需要根据 categories 获取分类的 name;
                $category_model = Category::findOne(['id' => intval($this->category_id)]);
                $this->category_name = $category_model->name ?: '';
                //首先把该标签置空
                $this->tags = [];
            }
            $this->modified = time();
            return true;
        } else {
            return false;
        }
    }

    /**
     * @access 更新后操作
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $beforeCount = $afterCount = false;
        if ($insert) {
            //表示插入操作  表示是不是 修改了字段的状态
            $beforeCount = false;
            $afterCount = $this->status == static::STATUS_PUBLISH;
            //还需要获取是不是 分类 改变了
        } else {
            //表示更新操作
            if (isset($changedAttributes['status'])) {
                $beforeCount = $changedAttributes['status'] == static::STATUS_PUBLISH;
                $afterCount = $this->status == static::STATUS_PUBLISH;
            }
            //还需要获取是不是 分类 改变了
        }
        $this->insertCategory($this->inputCategory, $beforeCount, $afterCount);
        $this->insertTags($this->inputTags, $beforeCount, $afterCount);
//        $this->insertAttachment($this->inputAttachments);
    }


    /**
     * @param $categoryId
     * @param bool $beforeCount
     * @param bool $afterCount
     * @return bool
     */
    public function insertCategory($categoryId, $beforeCount = true, $afterCount = true)
    {
        //如果之前没有 统计上  false;
        //                          现在 true 只需要 更新 分类的 数量, 还有tag 中 添加文章的 id name;
        //                          现在 false 则 不需要 任何信息,只需要 更新 分类的数量就好;
        //如果之前已经统计上   true;
        //                          现在 true 还需要判断是不是修改了 属性,修改 需要更新现在跟之前的 tag的 数量跟 文章id name 数组
        //                          现在 false tag数量减一  文章的 id name 删除;
        if (!Category::find()->andWhere(['id' => intval($categoryId)])->one()) {
            //表示没有获取到 分类
            return;
        }
        $postId = $this->attributes['id'] ?: 0;
        $postTitle = $this->attributes['title'] ?: '';
        if ($beforeCount === false) {
            //之前不需要统计
            if ($afterCount === true) {
                //更新当前的分类的id  文章的 idname 数组
                $flag = 'add';
                Category::updateCategoryTagCountIdname($categoryId, $postId, $postTitle, $flag);
            } else {

            }
        } else {
            //之前已经统计
            $oldCategoryId = $this->oldAttributes['category_id'] ?: 0;
            if ($afterCount === true) {
                if ($oldCategoryId != $categoryId && $oldCategoryId == 0) {
                    //更新之前的 分类的数量跟 文章的 idname 数组
                    $flag = 'remove';
                    Category::updateCategoryTagCountIdname($oldCategoryId, $postId, $postTitle, $flag);
                    //更新当前的分类的id  文章的 idname 数组
                    $flag = 'add';
                    Category::updateCategoryTagCountIdname($categoryId, $postId, $postTitle, $flag);
                } else {

                }
            } else {
                //之前 计入 现在不计入  需要把之前的 分类 count-1 idname 数组移除
                //更新之前的 分类的数量跟 文章的 idname 数组
                $flag = 'remove';
                Category::updateCategoryTagCountIdname($oldCategoryId, $postId, $postTitle, $flag);
            }
        }
        return true;
    }


    /**
     * 添加文章所属的标签
     * @access public
     * @param $tags
     * @param bool $beforeCount
     * @param bool $afterCount
     * @return bool
     * @todo 需要修改成  如果是隐藏的情况下 不修改标签的数量值 还有idname 数组
     */
    public function insertTags($tags, $beforeCount = true, $afterCount = true)
    {
        if (!is_array($tags)) {
            return;
        }
        //修改循环修改标签
        $oldTags = $this->oldAttributes['tags'];
        //新增的话  需要首先添加tag 到数据库中
        Tag::batchInsertTag($tags);
        $postId = $this->attributes['id'] ?: 0;
        $postTitle = $this->attributes['title'] ?: '';
        $this->deletePostTags($postId);
        //判断是不是新添加
        if (!$this->isNewRecord) {
            foreach ($oldTags as $tag) {
                //首先先把之前的tags 都清除
                //更新之前的 分类的数量跟 文章的 idname 数组
                $flag = 'remove';
                Tag::updateCategoryTagCountIdname(Tag::getTagIdByTagName($tag), $postId, $postTitle, $flag);
            }
        }
        //然后添加新的tags
        $tagArr = [];
        foreach ($tags as $tag) {
            //更新当前的分类的id  文章的 idname 数组
            //需要获取tag的id
            $id = Tag::getTagIdByTagName($tag);
            if ($afterCount) {
                //如果 修改之后改为 公开的话
                $flag = 'add';
                Tag::updateCategoryTagCountIdname($id, $postId, $postTitle, $flag);
            }
            $tagArr[] = ['id' => $id, 'name' => $tag];
        }
        if (!empty($tagArr)) {
            $this->updatePostTags($postId, $tagArr);
        }
        return true;
    }

    /**
     *删除post 标签
     * @param $postId 文章的id 删除文章中的所有的tag 字段中的数据
     */
    public function deletePostTags($postId)
    {
        $collection = Yii::$app->mongodb->getCollection(self::collectionName());
        $collection->update(
            ['id' => intval($postId)],
            ['$set' => ['tags' => []]],
            ['upsert' => false, 'multi' => false]
        );
    }

    /**
     *更新post 标签
     * @param $postId
     * @param $tagsArr  新的tag数组
     */
    public function updatePostTags($postId, $tagsArr)
    {
        $collection = Yii::$app->mongodb->getCollection(self::collectionName());
        $collection->update(
            ['id' => intval($postId)],
            ['$set' => ['tags' => $tagsArr]],
            ['upsert' => false, 'multi' => false]
        );
    }

    /**
     * 删除文章之后做的操作
     * @access public
     */
    public function afterDelete()
    {
        parent::afterDelete();
        $this->deleteCategory($this->status == static::STATUS_PUBLISH);
        $this->deleteTags($this->status == static::STATUS_PUBLISH);
//      $this->deleteAttachments();
    }

    /**
     * 更新分类数组
     * @access public
     * @param $isPublic  标志是不是公开的文章
     * @todo 如果是公开的文章  需要修改 category的 count-1 还有 删除idname
     * @return boolean
     */
    public function deleteCategory($isPublic)
    {
        if ($isPublic) {
            //更新之前的 分类的数量跟 文章的 idname 数组
            $flag = 'remove';
            Category::updateCategoryTagCountIdname($this->category_id, $this->id, $this->title, $flag);
        }
        return true;
    }

    /**
     * 更新分类数组
     * @access public
     * @param $isPublic  标志是不是公开的文章
     * @todo 如果是公开的文章  需要修改 category的 count-1 还有 删除idname
     * @return boolean
     */
    public function deleteTags($isPublic)
    {
        $tags = $this->tags;
        if ($isPublic) {
            //更新之前的 分类的数量跟 文章的 idname 数组
            foreach ($tags as $v) {
                $flag = 'remove';
                Tag::updateCategoryTagCountIdname($v['id'], $this->id, $this->title, $flag);
            }
        }
        return true;
    }


    public static function find()
    {
        //get_called_class 表示获取静态调用的类名
        return new PostQuery(get_called_class(),[]);
    }

    /**
     * 获取自增主键值
     * @access public
     */
    public function get_autoincrement_id()
    {
        $collection = Yii::$app->mongodb->getCollection('counters');
        $id_arr = $collection->findAndModify(['_id' => 'post_id'], ['$inc' => ['count' => 1]], ['fields' => ['count' => 1, '_id' => 0]]);
        $this->id = $id_arr['count'];
    }


}