<?php

namespace common\models;

use yii;
use yii\mongodb\ActiveRecord;

/**
 * @property string $name
 * @property integer $user
 * @property string $value
 */
class Option extends ActiveRecord
{

    private static $_options;

    private static $cacheKey = 'option';

//    /**
//     * @inheritdoc
//     */
//    public static function tableName()
//    {
//        return '{{%options}}';
//    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'user', 'value'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'user' => 'User',
            'value' => 'Value',
        ];
    }


    /**
     * 获取网站配置数组
     * @return array
     */
    public static function getOptions()
    {
        if (self::$_options == null) {
            $options = Option::find()->asArray()->all();
//            self::$_options = yii\helpers\ArrayHelper::map($options, 'name', 'value');
            foreach ($options[0] as $k => $v) {
                if ($k != '_id') {
                    self::$_options[$k] = $v;
                }
            }
        }
        return self::$_options;
    }

    /**
     * 更新设置选项
     * @param $data
     */
    public static function updateOption($data)
    {
//        foreach ($data as $k => $v) {
//            $options = self::getOptions();
//            if (array_key_exists($k, $options)) {
//                self::updateAll(['value' => $v], ['name' => $k]);
//            }
//        }
        $collection = Yii::$app->mongodb->getCollection('option');
        $collection->update(['_id' => 0], $data, ['upsert' => false, 'multi' => false]);
        self::clearOptionCache();//清空缓存
    }

    /**
     * 获取系统配置单个值
     * @param $name
     * @return null
     */
    public static function getOptionValue($name)
    {
        //缓存中获取
        $options = self::getOptionCache();
        return array_key_exists($name, $options) ? $options[$name] : null;
    }

    /**
     * 清除系统配置缓存
     * @return bool
     */
    public static function clearOptionCache()
    {
        return Yii::$app->cache->delete(self::$cacheKey);
    }

    /**
     * 设置系统配置缓存
     * @return array|mixed
     */
    public static function getOptionCache()
    {
        $options = Yii::$app->cache->get(self::$cacheKey);
        if ($options === false) {
            $options = self::getOptions();
            Yii::$app->cache->set(self::$cacheKey, $options);
        }
        return $options;
    }
}
