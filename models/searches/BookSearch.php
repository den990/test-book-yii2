<?php

namespace app\models\searches;

use app\models\Book;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class BookSearch extends Model
{
    public $year;
    public $title;
    public $isbn;

    public function rules()
    {
        return [
            [['year'], 'integer'],
            [['title', 'isbn'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'year' => 'Year',
            'title' => 'Title',
            'isbn' => 'Isbn',
        ];
    }


    public function search($params)
    {
        $query = Book::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->andWhere('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['year' => $this->year]);
        $query->andFilterWhere(['like', 'isbn', $this->isbn]);

        return $dataProvider;
    }
}