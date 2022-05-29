<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "FORMPUBLISH".
 *
 * @property float $FORMPUBLISHID
 * @property float $FORMID
 * @property float $FORMQUESTIONID
 * @property int|null $STAFF
 * @property int|null $LECTURER
 * @property int|null $STUDENT
 * @property int|null $PUBLICS
 */
class FormPublish extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'FORMPUBLISH';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['FORMPUBLISHID', 'FORMID', 'FORMQUESTIONID'], 'number'],
            [['FORMID', 'FORMQUESTIONID'], 'required'],
            [['STAFF', 'LECTURER', 'STUDENT', 'PUBLICS'], 'integer'],
            [['FORMPUBLISHID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'FORMPUBLISHID' => 'Formpublishid',
            'FORMID' => 'Formid',
            'FORMQUESTIONID' => 'Formquestionid',
            'STAFF' => 'Staff',
            'LECTURER' => 'Lecturer',
            'STUDENT' => 'Student',
            'PUBLICS' => 'Publics',
        ];
    }
}
