<?php

namespace app\models\searches;

use app\models\Author;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class AuthorSearch extends Model
{
    public $name;

    public function rules()
    {
        return [
            [['name'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Name'
        ];
    }


    public function search($params)
    {
        $query = Author::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->andWhere('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'full_name', $this->name]);

        return $dataProvider;
    }
}