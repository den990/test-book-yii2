<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "book".
 *
 * @property int $id УИ записи
 * @property string $title Название
 * @property int $year Год
 * @property string $description Описание
 * @property int $cover url Фото
 * @property string $created_at Дата создания
 * @property string $updated_at Дата обновления
 *
 * @property Author[] $authors
 */

class Book extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%book}}';
    }

    public function rules()
    {
        return [
            [['title','year'], 'required'],
            ['year', 'integer', 'min' => 1000, 'max' => (int)date('Y')],
            ['isbn', 'string', 'max' => 64],
            [['isbn'], 'unique'],
            ['cover', 'string', 'max' => 512],
            ['description', 'string'],
            [['updated_at', 'created_at'], 'safe']
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

    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'title' => 'Title',
            'year' => 'Year',
            'description' => 'Description',
            'isbn' => 'Isbn',
            'cover' => 'Cover',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
        ];
    }

    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('{{%book_author}}', ['book_id' => 'id']);
    }
}