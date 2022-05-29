<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "USERDATA".
 *
 * @property float $USERDATAID
 * @property string|null $USERDATAEMAIL
 * @property string $USERDATAPASSWORD
 * @property float $USERDATAJOBID
 * @property string $USERDATAAUTHKEY
 */
class UserData extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'USERDATA';
    }

    public static function primaryKey()
    {
        return ['USERDATAID'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['USERDATAID', 'USERDATAJOBID'], 'number'],
            [['USERDATAPASSWORD', 'USERDATAJOBID', 'USERDATAAUTHKEY'], 'required'],
            [['USERDATAEMAIL', 'USERDATAPASSWORD', 'USERDATAAUTHKEY'], 'string', 'max' => 100],
            [['USERDATAID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'USERDATAID' => 'Userdataid',
            'USERDATAEMAIL' => 'Userdataemail',
            'USERDATAPASSWORD' => 'Userdatapassword',
            'USERDATAJOBID' => 'Userdatajobid',
            'USERDATAAUTHKEY' => 'Userdataauthkey',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public static function findByEmail($email)
    {
        // Find whether such email exists in database
        return static::findOne(['USERDATAEMAIL' => $email]);
    }

    public static function findRole($email)
    {
        // Find whether such email exists in database
        return static::find()->select('USERDATAJOBID')->where("USERDATAEMAIL='$email'")->One();
    }

    public function getId()
    {
        return $this->USERDATAID;
    }

    public function getAuthKey()
    {
        // Return who is active user
        return $this->USERDATAAUTHKEY;
    }

    public function validateAuthKey($authKey)
    {
        return $this->USERDATAAUTHKEY === $authKey;
    }

    public function validatePassword($password)
    {
        return $this->USERDATAPASSWORD === $password;
    }

}
