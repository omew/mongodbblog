<?php

namespace app\models;

use Yii;
//mongodb activerecord
use yii\mongodb\ActiveRecord;
use yii\web\IdentityInterface;
/**
 * 该类是 user 认证类
 * This is the model class for table "{{%user}}".
 * 
 * @property integer $uid
 * @property string $name
 * @property string $password
 * @property string $mail
 * @property string $url
 * @property string $screenName
 * @property integer $created
 * @property integer $activated
 * @property integer $logged
 * @property string $group
 * @property string $authCode
 */
class User extends ActiveRecord implements IdentityInterface {

    /**
     * @inheritdoc
     * 验证规则
     */
    public function rules() {
        return [
            [['name', 'mail', 'password'], 'required', 'on' => ['create']],
            [['mail'], 'required', 'on' => ['update', 'profile']],
            [['password'], 'string', 'min' => 6, 'max' => 20],
            [['name'], 'string', 'max' => 32, 'on' => ['create']],
            [['screenName'], 'string', 'max' => 32],
            [['name'], 'checkName', 'on' => ['create']],
            [['screenName'], 'checkName', 'skipOnEmpty' => false],
            [['mail'], 'email'],
            [['mail', 'url'], 'string', 'max' => 200],
            [['name'], 'unique', 'on' => ['create']],
            [['mail', 'screenName'], 'unique'],
        ];
    }

    /**
     * 表明ac 对应的表明
     * @inheritdoc
     */
    public static function collectionName() {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['uid' => $id,]);
    }

    /**
     * @inheritdoc
     * api 交互的时候使用的接口
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['name' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId() {
//        return $this->id;
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->authCode;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->authCode === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        //验证两次输入的密码是不是一致
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     * @param string $password
     * @return string
     */
    public function generatePassword($password) {
        return Yii::$app->security->generatePasswordHash($password);
    }

}
