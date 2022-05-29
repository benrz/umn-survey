<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "FORMANSWER".
 *
 * @property float $FORMANSWERID
 * @property string $USEREMAIL
 * @property float $FORMID
 * @property string $FORMANSWERDATE
 * @property int|null $FORMANSWERSTATUS
 */
class FormAnswer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'FORMANSWER';
    }

    public static function primaryKey()
    {
        return ['FORMANSWERID'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['FORMANSWERID', 'FORMID'], 'number'],
            [['USEREMAIL', 'FORMID', 'FORMANSWERDATE'], 'required'],
            [['FORMANSWERSTATUS'], 'integer'],
            [['USEREMAIL'], 'string', 'max' => 100],
            [['FORMANSWERDATE'], 'string', 'max' => 7],
            [['FORMANSWERID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'FORMANSWERID' => 'Form Answer ID',
            'USEREMAIL' => 'User Email',
            'FORMID' => 'Form ID',
            'FORMANSWERDATE' => 'Form Answer Date',
            'FORMANSWERSTATUS' => 'Form Answer Status',
        ];
    }
}
