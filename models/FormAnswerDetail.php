<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "FORMANSWERDETAIL".
 *
 * @property float $FORMANSWERDETAILID
 * @property float $FORMQUESTIONID
 * @property float $FORMANSWERID
 * @property string $FORMANSWERDETAILVALUE
 */
class FormAnswerDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'FORMANSWERDETAIL';
    }

    public static function primaryKey()
    {
        return ['FORMANSWERDETAILID'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['FORMANSWERDETAILID', 'FORMQUESTIONID', 'FORMANSWERID'], 'number'],
            [['FORMQUESTIONID', 'FORMANSWERID', 'FORMANSWERDETAILVALUE'], 'required'],
            [['FORMANSWERDETAILVALUE'], 'string', 'max' => 100],
            [['FORMANSWERDETAILID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'FORMANSWERDETAILID' => 'Formanswerdetailid',
            'FORMQUESTIONID' => 'Formquestionid',
            'FORMANSWERID' => 'Formanswerid',
            'FORMANSWERDETAILVALUE' => 'Formanswerdetailvalue',
        ];
    }
}
