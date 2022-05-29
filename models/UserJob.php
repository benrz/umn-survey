<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "USERJOB".
 *
 * @property float $USERJOBID
 * @property string $USERJOBNAME
 */
class UserJob extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'USERJOB';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['USERJOBID'], 'number'],
            [['USERJOBNAME'], 'required'],
            [['USERJOBNAME'], 'string', 'max' => 100],
            [['USERJOBID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'USERJOBID' => 'Userjobid',
            'USERJOBNAME' => 'Userjobname',
        ];
    }
}
