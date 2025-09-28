<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id УИ
 * @property string $email Email
 * @property string $phone Телефон
 * @property string $password Sha256 хэш пароля
 * @property string $updated_date Дата обновления
 * @property string $created_date Дата добавления
 */

class User extends ActiveRecord implements IdentityInterface
{
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            [['email', 'phone', 'password'], 'string'],
            [['email'], 'email'],
            ['phone', 'match', 'pattern'=>'/^\+?[0-9\-\s]{6,30}$/'],
            [['updated_at', 'created_date'], 'safe']
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => function () { return date('Y-m-d H:i:s'); },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return hash(
            'sha256',
            $this->id
            . $this->password
            . Yii::$app->request->cookieValidationKey
            . Yii::$app->request->userIP
        );
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
}
