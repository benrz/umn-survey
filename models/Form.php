<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "FORM".
 *
 * @property float $FORMID
 * @property float $FORMLISTID
 * @property string $FORMDATESTART
 * @property string $FORMDATEEND
 * @property float $FORMSTATUS
 */
class Form extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'FORM';
    }

    public static function primaryKey()
    {
        return ['FORMID'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['FORMID', 'FORMLISTID', 'FORMSTATUS'], 'number'],
            [['FORMLISTID'], 'required'],
            // [['FORMDATESTART', 'FORMDATEEND'], 'string', 'max' => 7],
            [['FORMID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'FORMID' => 'ID',
            'FORMLISTID' => 'Form List ID',
            'FORMDATESTART' => 'Start Date',
            'FORMDATEEND' => 'End Date',
            'FORMSTATUS' => 'Form Status',
        ];
    }
    
    public function getFormlist(){
        return $this->hasOne(FormList::className(), ['FORMLISTID' => 'FORMLISTID']);
    }
}
