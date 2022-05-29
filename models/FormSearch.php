<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Form;
use app\models\FormList;

/**
 * FormSearch represents the model behind the search form of `app\models\Form`.
 */
class FormSearch extends Form
{
    public $FORMLISTTITLE;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['FORMID', 'FORMLISTID','FORMSTATUS'], 'number'],
            [['FORMDATESTART', 'FORMDATEEND', 'FORMLISTTITLE'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Form::find()
            ->select([
                'FORM.FORMID', 'FORM.FORMDATESTART', 'FORM.FORMDATEEND', 'FORM.USERJOBID', 'FORM.FORMSTATUS',
                'FORMLIST.FORMLISTID', 'FORMLIST.FORMLISTTOTALSECTION', 
                'FORMLIST.FORMLISTTOTALQUESTION', 'FORMLIST.FORMLISTTITLE'])
            ->joinWith(['formlist'])
            ->where(['FORM.FORMSTATUS' => 1]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['FORMLISTTITLE'] = [
            'asc' => ['FORMLISTTITLE' => SORT_ASC],
            'desc' => ['FORMLISTTITLE' => SORT_DESC]
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'FORM.FORMID' => $this->FORMID,
            'FORM.FORMLISTID' => $this->FORMLISTID,
        ]);

        $query->andFilterWhere(['like', 'FORMDATESTART', $this->FORMDATESTART])
            ->andFilterWhere(['like', 'FORMDATEEND', $this->FORMDATEEND])
            ->andFilterWhere(['like', 'FORMLISTTITLE', $this->FORMLISTTITLE]);

        return $dataProvider;
    }
}
