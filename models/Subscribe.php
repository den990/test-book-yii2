<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

/**
 * This is the model class for table "subscribe".
 *
 * @property int $id УИ записи
 * @property int author_id УИ author
 * @property string $phone Телефон
 * @property string $updated_at Дата создания
 *
 * @property Author $author
 */

class Subscribe extends ActiveRecord
{
    const SMS_PILOT_URL = 'https://smspilot.ru/api.php';

    public static function tableName()
    {
        return '{{%subscribe}}';
    }

    public function rules()
    {
        return [
            [['author_id', 'phone'], 'required'],
            ['phone', 'match', 'pattern'=>'/^\+?[0-9\-\s]{6,30}$/'],
            ['author_id', 'exist', 'targetClass' => Author::class, 'targetAttribute' => 'id'],
            [['updated_at'], 'safe']
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => function () { return date('Y-m-d H:i:s'); },
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'author_id' => 'Author Id',
            'phone' => 'Phone',
            'created_at' => 'Created at',
        ];
    }


    public function subscribe()
    {
        if (self::find()->where(['author_id' => $this->author_id, 'phone' => $this->phone])->exists()) {
            Yii::$app->session->setFlash('warning', 'Вы уже подписаны на этого автора.');
            return false;
        }

        if (!$this->save(false)) {
            Yii::$app->session->setFlash('error', 'Не удалось подписаться. Проверьте введённые данные.');
            return false;
        }

        $text = "Вы подписались на автора: {$this->author->full_name}";
        $phone = $this->phone;

        try {
            $client = new Client(['transport' => 'yii\httpclient\CurlTransport']);
            $response = $client->createRequest()
                ->setUrl(self::SMS_PILOT_URL)
                ->setData([
                    'send' => $text,
                    'to' => $phone,
                    'apikey' => Yii::$app->params['smsPilotApiKey'],
                    'format' => 'json',
                ])
                ->send();

            if ($response->isOk) {
                Yii::info("SMS sent successfully: " . $response->content, __METHOD__);
            } else {
                Yii::error("SMS error: " . $response->content, __METHOD__);
            }
        } catch (\Exception $e) {
            Yii::error("SMS exception: " . $e->getMessage(), __METHOD__);
        }

        Yii::$app->session->setFlash('success', 'Вы успешно подписались на автора.');
        return true;
    }

    public static function notifySubscribersAtNewBook($authorId, $bookTitle)
    {
        $phonesArray = ArrayHelper::getColumn(self::find()->where(['author_id' => $authorId])->all(), 'phone');

        if (empty($phonesArray)) {
            return;
        }

        $phones = implode(',', $phonesArray);
        $text = "Новая книга от автора: {$bookTitle}";

        try {
            $client = new Client(['transport' => 'yii\httpclient\CurlTransport']);
            $response = $client->createRequest()
                ->setUrl(self::SMS_PILOT_URL)
                ->setData([
                    'send' => $text,
                    'to' => $phones,
                    'apikey' => Yii::$app->params['smsPilotApiKey'],
                    'format' => 'json',
                ])
                ->send();

            if ($response->isOk) {
                Yii::info("SMS sent to all subscribers: " . $response->content, __METHOD__);
            } else {
                Yii::error("SMS sending error: " . $response->content, __METHOD__);
            }
        } catch (\Exception $e) {
            Yii::error("SMS exception: " . $e->getMessage(), __METHOD__);
        }
    }

    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}
