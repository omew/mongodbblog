<?php
/**
 * @author: timeless [<https://github.com/timelesszhuang>]
 */

namespace common\models;

use yii;
use common\helpers\StringHelper;
use yii\helpers\Html;
USE Yii\MongoDB\ActiveRecord;


/**
 * @property int|string authorId
 * @property string type
 * @property mixed status
 * @property int _id
 * @property mixed postId
 */
class Page extends ActiveRecord
{
//    use AttachmentOperationTrait;
    const STATUS_PUBLISH = 'publish';
    const STATUS_HIDDEN = 'hidden';

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
        return 'page';
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
        ];
    }

    /**
     *只要是集成mongodb/activerecord的子类都需要实现该方法
     *available attributes
     */
    public function attributes()
    {
        return ['_id', 'id', 'title', 'slug', 'created', 'modified', 'text', 'order', 'authorId', 'authorName', 'template', 'status', 'password', 'commentsNum', 'allowComment', 'allowPing', 'allowFeed', 'attachments'];
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
    }


    /**
     * 删除文章之后做的操作
     * @access public
     */
    public function afterDelete()
    {
        parent::afterDelete();
    }


    /**
     * 获取自增主键值
     * @access public
     */
    public function get_autoincrement_id()
    {
        $collection = Yii::$app->mongodb->getCollection('counters');
        $id_arr = $collection->findAndModify(['_id' => 'page_id'], ['$inc' => ['count' => 1]], ['fields' => ['count' => 1, '_id' => 0]]);
        $this->id = $id_arr['count'];
    }


}