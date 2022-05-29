<?php

namespace app\models;
use yii\web\UploadedFile;

use Yii;

/**
 * This is the model class for table "FORMQUESTION".
 *
 * @property float $FORMQUESTIONID
 * @property float $FORMLISTID
 * @property string $FORMQUESTIONNAME
 * @property float $FORMQUESTIONTYPEID
 * @property int|null $FORMREQUIRED
 * @property string|null $FORMDESCRIPTION
 * @property string|null $FORMIMAGE
 * @property float $FORMQUESTIONPOSITION
 * @property float $FORMQUESTIONSECTION
 */
class FormQuestion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'FORMQUESTION';
    }

    public static function primaryKey()
    {
        return ['FORMQUESTIONID'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['FORMQUESTIONID', 'FORMLISTID', 'FORMQUESTIONTYPEID', 'FORMQUESTIONPOSITION', 'FORMQUESTIONSECTION'], 'number'],
            // [['FORMLISTID', 'FORMQUESTIONNAME', 'FORMQUESTIONTYPEID', 'FORMQUESTIONPOSITION', 'FORMQUESTIONSECTION'], 'required'],
            [['FORMQUESTIONNAME', 'FORMQUESTIONTYPEID'], 'required'],
            [['FORMREQUIRED'], 'integer'],
            [['FORMQUESTIONNAME', 'FORMDESCRIPTION'], 'string', 'max' => 200],
            [['FORMIMAGE'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['FORMQUESTIONID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'FORMQUESTIONID' => 'Form Question ID',
            'FORMLISTID' => 'Form List ID',
            'FORMQUESTIONNAME' => 'Form Question Name',
            'FORMQUESTIONTYPEID' => 'Form Question Type ID',
            'FORMREQUIRED' => 'Form Required',
            'FORMDESCRIPTION' => 'Form Description',
            'FORMIMAGE' => 'Form Image',
            'FORMQUESTIONPOSITION' => 'Form Question Position',
            'FORMQUESTIONSECTION' => 'Form Question Section',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            if($this->FORMIMAGE != null)
                $this->FORMIMAGE->saveAs('uploads/' . $this->FORMIMAGE->baseName . '.' . $this->FORMIMAGE->extension);
            return true;
        } else {
            return false;
        }
    }
}
