<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
class Country extends Model{
    public function question($id){
        $command = Yii::$app->db->createCommand("SELECT a.formQuestionID AS ID,a.formQuestionName AS Name,a.formDescription AS Description,a.formQuestionTypeID AS ID_type,a.formRequired AS Required
        FROM formquestion a, formquestiontype b,formlist c
        WHERE a.formQuestionTypeID = b.formQuestionTypeID
        AND a.formListID = c.formListID
        AND a.formListID = $id
        ORDER BY a.formQuestionPosition asc")->queryAll();
        return $command;
    }
    public function questionID(){
        $command = Yii::$app->db->createCommand("SELECT formQuestionID AS ID FROM formquestion")->queryAll();
        return $command;
    }
    public function checkbox(){
        $command = Yii::$app->db->createCommand("SELECT formQuestionID AS ID, formQuestionValue AS val  FROM formQuestionOption ORDER BY formQuestionOptionRowPosition")->queryAll();
        return $command;
    }
     // ini untuk insert answer pada iterasi pertama
    public function insertAnswer($sequence,$email,$idFormAnswer,$id,$value,$formListID){
        $commandFormAnswer = Yii::$app->db->createCommand("INSERT INTO FORMANSWER(FORMANSWERID,USEREMAIL,FORMID,FORMANSWERDATE,FORMANSWERSTATUS) VALUES($idFormAnswer,'$email',$formListID,SYSDATE,1)")->execute();
        $commandFormAnswerDetail = Yii::$app->db->createCommand("INSERT INTO FORMANSWERDETAIL(FORMANSWERDETAILID,FORMQUESTIONID,FORMANSWERID,FORMANSWERDETAILVALUE) VALUES($sequence,$id,$idFormAnswer,'$value')")->execute();

    }
    //ini untuk menghitung banyak formanswerdetailnya, jadi bisa dijadiin ID untuk formanswerdetail karena harus unik
    public function count(){
        $command = Yii::$app->db->createCommand("SELECT COUNT(*) AS SEQ FROM FORMANSWERDETAIL")->queryAll();
        return $command;
    }
    // ini untuk generate jumlah formanswer, untuk dijadiin ID formanswer karena harus unik
    public function countFormAnswer(){
        $command = Yii::$app->db->createCommand("SELECT COUNT(*) AS SEQ FROM FORMANSWER")->queryAll();
        return $command;
    }
    // ini untuk insertanswerdetail, kalau insertanswer itu masukin data ke formanswer juga
    public function insertAnswerDetail($sequence,$idFormAnswer,$id,$value){
        $commandFormAnswerDetail = Yii::$app->db->createCommand("INSERT INTO FORMANSWERDETAIL(FORMANSWERDETAILID,FORMQUESTIONID,FORMANSWERID,FORMANSWERDETAILVALUE) VALUES($sequence,$id,$idFormAnswer,'$value')")->execute();

    }
}
?>