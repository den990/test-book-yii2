<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "author".
 *
 * @property int $id УИ записи
 * @property string full_name Название
 * @property string $created_at Дата создания
 * @property string $updated_at Дата обновления
 *
 * @property Author[] $authors
 */

class Author extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%author}}';
    }

    public function rules()
    {
        return [
            ['full_name','required'],
            ['full_name','string','max' => 255],
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
            'full_name' => 'Full name',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
        ];
    }

    public function getBooks()
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])
            ->viaTable('{{%book_author}}', ['author_id' => 'id']);
    }

    public function getSubscribe()
    {
        return $this->hasMany(Subscribe::class, ['author_id' => 'id']);
    }

    public static function topAuthorsByYear(int $year, int $limit = 10)
    {
        return self::find()
            ->select(['author.*', 'book_count' => new Expression('COUNT(book.id)')])
            ->joinWith(['books book'])
            ->where(['book.year' => $year])
            ->groupBy('author.id')
            ->orderBy(['book_count' => SORT_DESC])
            ->limit($limit)
            ->all();
    }
}
