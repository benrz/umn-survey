<?php

namespace app\models;

use PDO;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class SendFrom extends Model
{
    public $subject;
    public $body;
    public $verifyCode;
    public $PRODI = array();
    public $user = array();
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['subject', 'body','user'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact()
    {
        $this->user = ($_POST['SendFrom']['user']);
        $nuser = sizeof($_POST['SendFrom']['user']);
        $this->PRODI = ($_POST['SendFrom']['PRODI']);
        $query = new \yii\db\Query;
        for($i=0;$i<$nuser;$i++){
            if($this->user[$i] == 3){
                $query
                ->select('USERDATAEMAIL')
                ->from('STUDENT')
                ->WHERE(['PRODIID' => $this->PRODI]);
                $command = $query->createCommand();
                $email1 = $command->queryAll(\PDO::FETCH_COLUMN);
            }else{
                if(empty($email1)){
                    $email1 = array();
                }
            }
            if($this->user[$i] == 2){
                $query
                ->select('USERDATAEMAIL')
                ->from('LECTURER')
                ->WHERE(['PRODIID' => $this->PRODI]);
                $command = $query->createCommand();
                $email2 = $command->queryAll(\PDO::FETCH_COLUMN);
            }else{
                if(empty($email2)){
                    $email2 = array();
                }
            }
            if($this->user[$i] == 4){
                $query
                ->select('USERDATAEMAIL')
                ->from('USERDATA')
                ->WHERE(['USERDATAJOBID' => 4]);
                $command = $query->createCommand();
                $email3 = $command->queryAll(\PDO::FETCH_COLUMN);
            }else{
                if(empty($email3)){
                    $email3 = array();
                }
            }
        }
            $email = array_merge($email1,$email2,$email3);
            // print_r($this->user);
            // print_r ($this->PRODI);
            // print_r ($email1);
            // print_r ($email2);
            // print_r ($email3);
            // print_r ($email);
            // die();
        Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();

        return true;
    }
}
