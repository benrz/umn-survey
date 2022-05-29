<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "FORMQUESTIONOPTION".
 *
 * @property float $FORMQUESTIONOPTIONID
 * @property float $FORMQUESTIONID
 * @property string|null $FORMQUESTIONVALUE
 * @property string|null $FORMQUESTIONOPTIONOTHERS
 * @property float $FORMQUESTIONOPTIONROWPOSITION
 * @property int|null $FORMQUESTIONOPTIONNEXTSECTION
 */
class FormQuestionOption extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'FORMQUESTIONOPTION';
    }

    public static function primaryKey()
    {
        return ['FORMQUESTIONOPTIONID'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['FORMQUESTIONOPTIONID', 'FORMQUESTIONID', 'FORMQUESTIONOPTIONROWPOSITION'], 'number'],
            // [['FORMQUESTIONID', 'FORMQUESTIONOPTIONROWPOSITION'], 'required'],
            [['FORMQUESTIONOPTIONNEXTSECTION'], 'integer'],
            [['FORMQUESTIONVALUE', 'FORMQUESTIONOPTIONOTHERS'], 'string', 'max' => 200],
            [['FORMQUESTIONOPTIONID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'FORMQUESTIONOPTIONID' => 'Form Question Option ID',
            'FORMQUESTIONID' => 'Form Question ID',
            'FORMQUESTIONVALUE' => 'Form Question Value',
            'FORMQUESTIONOPTIONOTHERS' => 'Form Question Option Others',
            'FORMQUESTIONOPTIONROWPOSITION' => 'Form Question Option Row Position',
            'FORMQUESTIONOPTIONNEXTSECTION' => 'Form Question Option Next Section',
        ];
    }
}
