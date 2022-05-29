<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

    Class Answer extends Model{
        public function rules()
        {
            return[
                [['nama','umur'],'required'],
                [['nama'],'string','max' => 50],
            ];
        }
        public static function tableName(){
            return 'formanswerdetail';
        }
    }
?>