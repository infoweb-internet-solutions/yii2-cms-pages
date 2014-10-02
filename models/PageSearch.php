<?php

namespace infoweb\pages\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use infoweb\pages\models\Page;
use infoweb\pages\models\PageLang;

/**
 * PageSearch represents the model behind the search form about `app\models\Page`.
 */
class PageSearch extends Page
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'active'], 'integer'],
            [['name', 'content', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Page::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'active' => $this->active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        //$query->innerJoin(PageLang::tableName(), 'page_id = id');
        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
