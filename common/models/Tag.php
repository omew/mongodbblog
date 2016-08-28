<?php
/**
 * @author: mojifan [<https://github.com/mojifan>]
 */
namespace common\models;

use common\helpers\StringHelper;

class Tag extends Meta
{

    const TYPE = 'tag';

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
        ];
    }

    public function afterDelete()
    {
        return;
    }

    /**
     * 批量添加tag
     * @access public
     * @todo 判断下是不是已经字段添加成功
     * @param $tags 标签
     * @return boolean
     */
    public static function batchInsertTag($tags)
    {
        //首先 判断下
        $tagModel = new Tag();
        $rows = array();
        foreach ($tags as $tag) {
            //需要每一个都判断下是不是每一个都已经添加过
            if (!Tag::findOne(['name' => $tag])) {
                $tagModel = clone $tagModel;
                $tagModel->get_autoincrement_id();
                $rows[] = [
                    'id' => $tagModel->id,
                    'name' => $tag,
                    'slug' => $tag,
                    'type' => 'tag',
                ];
            }
        }
        if (!empty($rows)) {
            \Yii::$app->mongodb->createCommand()->batchInsert(self::collectionName(), $rows);
        }
        return true;
    }

    /**
     *更具 tag name 获取id
     * @access public
     * @param $tag
     * @return integer
     */
    public static function getTagIdByTagName($tag)
    {
        if ($tagModel = Tag::findOne(['name' => $tag])) {
            if (!empty($tagModel->id)) {
                return $tagModel->id;
            }
        }
        return 0;
    }
}