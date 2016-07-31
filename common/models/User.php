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
 * @property integer $addtime
 * @property string $group
 * @property string $authCode
 * @property mixed authcode
 */
class User extends ActiveRecord implements IdentityInterface
{

    const GROUP_VISITOR = 'visitor';
    const GROUP_SUBSCRIBER = 'subscriber';
    const GROUP_CONTRIBUTOR = 'contributor';
    const GROUP_EDITOR = 'editor';
    const GROUP_ADMINISTRATOR = 'administrator';

    /**
     * 用户所属组
     * @access public
     */
    public static function getUserGroup()
    {
        return [
            self::GROUP_ADMINISTRATOR => '管理员',
            self::GROUP_CONTRIBUTOR => '贡献者',
            self::GROUP_EDITOR => '编辑',
            self::GROUP_SUBSCRIBER => '关注者',
            self::GROUP_VISITOR => '访问者',
        ];
    }

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
            [['screenname'], 'string', 'max' => 32],
            [['name'], 'checkName', 'on' => ['create']],
            [['screenname'], 'checkName', 'skipOnEmpty' => false],
            [['mail'], 'email'],
            [['url'], 'url'],
            [['mail', 'url', 'desc'], 'string', 'max' => 200],
            [['name'], 'unique', 'on' => ['create']],
            [['mail', 'screenname'], 'unique'],
            [['group'], 'filter', 'filter' => function ($value) {
                if (!array_key_exists($value, self::getUserGroup())) {
                    //默认没有设置的话 表示是访客
                    return self::GROUP_VISITOR;
                }
                return $value;
            }, 'on' => ['create', 'update']],
        ];
    }


    /**
     * 验证字段合法性
     * @access public
     * @param $attribute
     * @param $params
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
        return ['_id', 'id', 'name', 'password', 'mail', 'screenname', 'desc', 'authcode', 'group', 'url', 'addtime'];
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
            'url' => '个人主页',
            'screenname' => '昵称',
            'addtime' => '创建时间',
            'group' => '角色',
            'authcode' => 'Auth Code',
            'desc' => '描述',
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
        return $this->id;
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
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->authcode = Yii::$app->security->generateRandomString();
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

    /**
     *更新数据之前
     * @param bool $insert 表示是不是插入的
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->scenario == 'create') {
                $this->generatePassword($this->password);
            } elseif ($this->scenario == 'update' || $this->scenario = 'profile') {
                if (trim($this->password) == '') {
                    //获取更新之前的字段值
                    $this->password = $this->getOldAttribute('password');
//                    $this->id = $this->getOldAttribute('id');
                } else {
                    $this->generatePassword($this->password);
                }
            }
            if ($insert) {
                //还需要更新 id字段 该字段需要自增
                $this->get_autoincrement_id();
                $this->addtime = time();
                $this->generateAuthKey();
            }
            if (trim($this->screenname) == '') {
                $this->screenname = $this->name;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取自增主键
     * @access public
     */
    public function get_autoincrement_id()
    {
        $collection = Yii::$app->mongodb->getCollection('counters');
        $id_arr = $collection->findAndModify(['_id' => 'user_id'], ['$inc' => ['count' => 1]], ['fields' => ['count' => 1, '_id' => 0]]);
        $this->id = $id_arr['count'];
    }

}
