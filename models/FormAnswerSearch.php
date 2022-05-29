<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FormAnswer;

/**
 * FormAnswerSearch represents the model behind the search form of `app\models\FormAnswer`.
 */
class FormAnswerSearch extends FormAnswer
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['FORMANSWERID', 'FORMID'], 'number'],
            [['USEREMAIL', 'FORMANSWERDATE'], 'safe'],
            [['FORMANSWERSTATUS'], 'integer'],
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
        $query = FormAnswer::find();
            // ->select('FORMANSWER.*')
            // ->innerJoinWith('FORM', '`FORM`.`FORMID` = `FORMANSWER`.`FORMID`')
            // ->where(['order.status' => Order::STATUS_ACTIVE])
            // ->with('orders')
            // ->all();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'FORMANSWERID' => $this->FORMANSWERID,
            'FORMID' => $this->FORMID,
            'FORMANSWERSTATUS' => $this->FORMANSWERSTATUS,
        ]);

        $query->andFilterWhere(['like', 'USEREMAIL', $this->USEREMAIL])
            ->andFilterWhere(['like', 'FORMANSWERDATE', $this->FORMANSWERDATE]);

        return $dataProvider;
    }
}
