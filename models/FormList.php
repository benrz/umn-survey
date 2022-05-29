<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "FORMLIST".
 *
 * @property float $FORMLISTID
 * @property string $FORMLISTTITLE
 * @property string $FORMLISTDATE
 * @property float $FORMLISTTOTALSECTION
 * @property float $FORMLISTTOTALQUESTION
 */
class FormList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'FORMLIST';
    }

    public static function primaryKey()
    {
        return ['FORMLISTID'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['FORMLISTID', 'FORMLISTTOTALSECTION', 'FORMLISTTOTALQUESTION'], 'number'],
            // [['FORMLISTTITLE', 'FORMLISTDATE', 'FORMLISTTOTALSECTION', 'FORMLISTTOTALQUESTION'], 'required'],
            [['FORMLISTTITLE', 'FORMLISTDATE'], 'required'],
            [['FORMLISTTITLE'], 'string', 'max' => 200],
            [['FORMLISTID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'FORMLISTID' => 'ID',
            'FORMLISTTITLE' => 'Title',
            'FORMLISTDATE' => 'Date',
            'FORMLISTTOTALSECTION' => 'Total Section',
            'FORMLISTTOTALQUESTION' => 'Total Question',
        ];
    }
}
