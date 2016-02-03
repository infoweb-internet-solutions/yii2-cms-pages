<?php

namespace infoweb\pages\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

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
        $integerFields = ['id', 'active'];
        
        if (Yii::$app->getModule('pages')->enablePrivatePages)
            $integerFields[] = 'public';
        
        return [
            [$integerFields, 'integer'],
            [['name'], 'safe'],
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
        
        $query->andFilterWhere(['language' => Yii::$app->language]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['name' => SORT_ASC]],
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
        
        // Join the entity model as a relation
        $query->joinWith(['translations']);
        
        // enable sorting for the related column
        $dataProvider->sort->attributes['name'] = [
            'asc' => ['name' => SORT_ASC],
            'desc' => ['name' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'active' => $this->active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        
        if (Yii::$app->getModule('pages')->enablePrivatePages)
            $query->andFilterWhere(['public' => $this->public]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
