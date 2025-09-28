<?php

namespace app\models\forms;

use app\models\Author;
use app\models\Book;
use app\models\Subscribe;
use yii\helpers\ArrayHelper;

class BookForm extends Book
{
    public $authorIds;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['authorIds'], 'each', 'rule' => ['integer']],
            [['authorIds'], 'required'],
        ]);
    }

    public function attributeLabels()
    {
        return  ArrayHelper::merge(parent::attributeLabels(),
        [
            'authorIds' => 'Authors'
        ]);

    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->unlinkAll('authors', true);
        if (!empty($this->authorIds)) {
            foreach ($this->authorIds as $authorId) {
                Subscribe::notifySubscribersAtNewBook($authorId, $this->title);
                $author = Author::findOne($authorId);
                if ($author) {
                    $this->link('authors', $author);
                }
            }
        }
    }

    public function loadAuthors()
    {
        $this->authorIds = ArrayHelper::getColumn($this->authors, 'id');
    }

}