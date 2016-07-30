<?php

namespace common\models;

use Yii;
//mongodb activerecord
use common\helpers\StringHelper;
use yii\mongodb\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * 该类是 user 认证类
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
 * @property mixed authcode
 */
class User extends ActiveRecord implements IdentityInterface
{

    /**
     * @inheritdoc
     * 验证规则
     */
    public function rules()
    {
        return [
            [['name', 'mail', 'password'], 'required', 'on' => ['create']],
            [['mail'], 'required', 'on' => ['update', 'profile']],
            [['password'], 'string', 'min' => 6, 'max' => 20],
            [['name'], 'string', 'max' => 32, 'on' => ['create']],
            [['screenName'], 'string', 'max' => 32],
            [['name'], 'checkName', 'on' => ['create']],
            [['screenname'], 'checkName', 'skipOnEmpty' => false],
            [['mail'], 'email'],
            [['mail'], 'string', 'max' => 200],
            [['name'], 'unique', 'on' => ['create']],
            [['mail', 'screenname'], 'unique'],
        ];
    }

    /**
     * 验证是不是包含空的字符串
     * @access public
     */
    public function checkName($attribute, $params)
    {

        if (!$this->hasErrors()) {
            if (($attribute == 'name') || ($attribute == 'screenName' && $this->$attribute != '')) {
                if (!StringHelper::checkCleanStr($this->$attribute)) {
                    $this->addError($attribute, $this->getAttributeLabel($attribute) . '只能为数字字母下划线横线');
                }
            }
        }
    }

    /**
     *只要是集成mongodb/activerecord的子类都需要实现该方法
     *available attributes
     */
    public function attributes()
    {
        return ['_id', 'id', 'name', 'password', 'mail', 'screenname', 'desc', 'authcode', 'addtime'];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'name' => '用户名',
            'password' => '密码',
            'mail' => '邮箱',
//            'url' => '个人主页',
            'screenname' => '昵称',
            'addtime' => '创建时间',
            'group' => '用户组',
            'authcode' => 'Auth Code',
        ];
    }


//    findIdentity是根据传递的id返回对应的用户信息，
//    getId返回用户id，
//    getAuthKey和validateAuthKey是作用于登陆中的--记住我。
//    这个authKey是唯一的，当再次登陆时，从cookie中获取authKey传递给validateAuthKey，验证通过，就登陆成功。

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $user = static::findOne(['id' => $id]);
        return $user;
    }

    /**
     * @inheritdoc
     * api 交互的时候使用的接口
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param  string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['name' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
//        var_dump($this->_id);
//        exit;
        return $this->id;
//        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authcode;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authcode === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        //验证两次输入的密码是不是一致
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     * @param string $password
     * @return string
     */
    public function generatePassword($password)
    {
        return Yii::$app->security->generatePasswordHash($password);
    }

}
