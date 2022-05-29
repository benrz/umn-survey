<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "FORMQUESTIONTYPE".
 *
 * @property float $FORMQUESTIONTYPEID
 * @property string $FORMQUESTIONTYPENAME
 */
class FormQuestionType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'FORMQUESTIONTYPE';
    }

    public static function primaryKey()
    {
        return ['FORMQUESTIONTYPEID'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['FORMQUESTIONTYPEID', 'FORMQUESTIONTYPENAME'], 'required'],
            [['FORMQUESTIONTYPEID'], 'number'],
            [['FORMQUESTIONTYPENAME'], 'string', 'max' => 100],
            [['FORMQUESTIONTYPEID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'FORMQUESTIONTYPEID' => 'Formquestiontypeid',
            'FORMQUESTIONTYPENAME' => 'Formquestiontypename',
        ];
    }
}
