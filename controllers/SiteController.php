<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\db\Connection;
use yii\db\Command;
use yii\db\DataReader;
use yii\db\Transaction;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Country;
use app\models\Answer;
use app\models\Form;
use app\models\Formlist;
use yii\widgets\ActiveForm;
use app\models\FormQuestion;
use app\models\FormQuestionOption;
use app\models\FormQuestionType;
use app\models\FormAnswerDetail;
use app\models\FormAnswer;
use app\models\UserJob;

use app\models\FormPublish;

class SiteController extends Controller
{
    public $layout = 'main_user';
    /**
     * {@inheritdoc}
     */
    private $questionID = array();
    private $surveyID = array();
    public $formListIDGlobal;
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $roleID = 1;
        
        if(Yii::$app->session->get('role') != NULL)
            $roleID = Yii::$app->session->get('role');
        if($roleID == 1)
            $jobName = 'PUBLICS';
        else
            $jobName = strtoupper(UserJob::find()->select(['USERJOBNAME'])->where(['USERJOBID' => $roleID])->one()['USERJOBNAME']);

        $model = Form::find()
        ->select([
            'FORM.FORMID', 'FORM.FORMDATESTART', 'FORM.FORMDATEEND', 'FORM.USERJOBID', 'FORM.FORMSTATUS',
            'FORMLIST.FORMLISTID', 'FORMLIST.FORMLISTTOTALSECTION', 
            'FORMLIST.FORMLISTTOTALQUESTION', 'FORMLIST.FORMLISTTITLE'])
        ->joinWith(['formlist'])
        ->where(['FORMSTATUS' => 1, $jobName => 1])->all();

        $data = array();
        $modelFormPublish = FormPublish::find()->where(['TREND' => 0])->all();
        // $modelFormAnswer = FormAnswer::find()->where(['FORMID' => $modelFormPublish[0]->FORMID])->all();

        $graph = array();
        $forRole = array();
        foreach($modelFormPublish as $formPublish){
            if(empty($graph["$formPublish[FORMID]"])){
                $graph["$formPublish[FORMID]"] = array();
            }
            if(empty($forRole["$formPublish[FORMQUESTIONID]"])){
                $forRole["$formPublish[FORMQUESTIONID]"] = array();
            }
            
            $formQuestionOption = FormQuestionOption::find()
                ->where(['FORMQUESTIONID' => $formPublish->FORMQUESTIONID])->all();

            $formQuestionTypeID = FormQuestion::find()->where(['FORMQUESTIONID' => $formPublish->FORMQUESTIONID])->one()['FORMQUESTIONTYPEID'];

            $countArray = [];
            foreach($formQuestionOption as $formOption){
                $countArray["$formOption->FORMQUESTIONVALUE"] = 0; // Awalnya diinisialisasi 0 semua
            }
    
            if($formQuestionTypeID == 6){
                $countArray = [];
                $countArray = ['1', '2', '3', '4', '5'];
                $countArray['1'] = 0;
                $countArray['2'] = 0;
                $countArray['3'] = 0;
                $countArray['4'] = 0;
                $countArray['5'] = 0;
            }

            $keys = array_keys( $countArray ); 
            for($x = 0; $x < sizeof($keys); $x++ ) { 
                // Masukin JUMLAH orang yang ngejawab pilihan A ke array A, dst.
                $countArray[$keys[$x]] = FormAnswerDetail::find()
                            ->where(['FORMANSWERDETAILVALUE' => $keys[$x]])
                            ->count();
            } 

            $graph["$formPublish[FORMID]"]["$formPublish[FORMQUESTIONID]"] = $countArray;

            if($formPublish["PUBLICS"] == 1){
                array_push($forRole["$formPublish[FORMQUESTIONID]"], 1);
            }
            if($formPublish["LECTURER"] == 1){
                array_push($forRole["$formPublish[FORMQUESTIONID]"], 2);
            }
            if($formPublish["STUDENT"] == 1){
                array_push($forRole["$formPublish[FORMQUESTIONID]"], 3);
            }
            if($formPublish["STAFF"] == 1){
                array_push($forRole["$formPublish[FORMQUESTIONID]"], 4);
            }
        }
        
        $modelFormTrend = FormPublish::find()->where(['TREND' => 1])->all();
        $graphTrend = array();
        $forRoleTrend = array();
        $countArray = array();
        $month_keys = array();
        $months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
        foreach($modelFormTrend as $formTrend){
            if(empty($graphTrend["$formTrend[FORMID]"])){
                $graphTrend["$formTrend[FORMID]"] = array();
            }
            if(empty($forRoleTrend["$formTrend[FORMQUESTIONID]"])){
                $forRoleTrend["$formTrend[FORMQUESTIONID]"] = array();
            }
            
            $formQuestionOption = FormQuestionOption::find()
                ->where(['FORMQUESTIONID' => $formTrend->FORMQUESTIONID])->all();

            $countArray = [];
            foreach($formQuestionOption as $formOption){
                foreach($months as $month){
                    $countArray["$formOption->FORMQUESTIONVALUE"][$month] = 0; // Awalnya diinisialisasi 0 semua
                }
            }

            $keys = array_keys( $countArray ); 
            $month_keys = array_keys( $countArray[$keys[0]]);
            for($x = 0; $x < sizeof($keys); $x++ ) { 
                for($y = 0; $y < sizeof($month_keys); $y++){
                    // Masukin JUMLAH orang yang ngejawab pilihan A ke array A, dst.
                    $countArray[$keys[$x]][$month_keys[$y]] += FormAnswerDetail::find()
                                ->innerJoin('FORMANSWER', 'FORMANSWER.FORMANSWERID = FORMANSWERDETAIL.FORMANSWERID')
                                ->innerJoin('FORM', 'FORM.FORMID = FORMANSWER.FORMID')
                                ->where(["FORMANSWERDETAILVALUE" => $keys[$x], "UPPER(to_char(FORMDATESTART, 'Mon'))" => $month_keys[$y]])
                                ->count();          
                }
            } 

            $graphTrend["$formTrend[FORMID]"]["$formTrend[FORMQUESTIONID]"] = $countArray;

            if($formTrend["PUBLICS"] == 1){
                array_push($forRoleTrend["$formTrend[FORMQUESTIONID]"], 1);
            }
            if($formTrend["LECTURER"] == 1){
                array_push($forRoleTrend["$formTrend[FORMQUESTIONID]"], 2);
            }
            if($formTrend["STUDENT"] == 1){
                array_push($forRoleTrend["$formTrend[FORMQUESTIONID]"], 3);
            }
            if($formTrend["STAFF"] == 1){
                array_push($forRoleTrend["$formTrend[FORMQUESTIONID]"], 4);
            }

        }

        // echo "<pre>";
        // print_r($forRoleTrend);
        // print_r($graphTrend);
        // print_r($graph);
        // print_r($modelFormTrend);
        // echo "<pre>";
        
        return $this->render('index',[
            'data' => $model,
            'graph' => $graph,
            'modelFormPublish' => $modelFormPublish,
            'forRole' => $forRole,
            'roleID' => $roleID,

            'graphTrend' => $graphTrend,
            'modelFormTrend' => $modelFormTrend,
            'forRoleTrend' => $forRoleTrend,
            'month_keys' => $month_keys,
            'countArray' => $countArray,
            ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    // public function actionLogout()
    // {    
    //     Yii::$app->user->logout();
    //     Yii::$app->session->set('role', 1);

    //     return $this->goHome();
    // }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionSurvey()
    {
        $roleID = 1;
        
        if(Yii::$app->session->get('role') != NULL)
            $roleID = Yii::$app->session->get('role');
        if($roleID == 1)
            $jobName = 'PUBLICS';
        else
            $jobName = strtoupper(UserJob::find()->select(['USERJOBNAME'])->where(['USERJOBID' => $roleID])->one()['USERJOBNAME']);

        $model = Form::find()
        ->select([
            'FORM.FORMID', 'FORM.FORMDATESTART', 'FORM.FORMDATEEND', 'FORM.USERJOBID', 'FORM.FORMSTATUS',
            'FORMLIST.FORMLISTID', 'FORMLIST.FORMLISTTOTALSECTION', 
            'FORMLIST.FORMLISTTOTALQUESTION', 'FORMLIST.FORMLISTTITLE'])
        ->joinWith(['formlist'])
        ->where(['FORMSTATUS' => 1, $jobName => 1])->all();

        $data = array();
        return $this->render('survey',['data' => $model]);
    }
    public function actionGraph()
    {
        return $this->render('index');
    }
    public function actionForm($formID)
    {
        $formlistID = Form::find()
        ->select([
            'FORM.FORMID', 'FORM.FORMDATESTART', 'FORM.FORMDATEEND', 'FORM.USERJOBID', 'FORM.FORMSTATUS',
            'FORMLIST.FORMLISTID', 'FORMLIST.FORMLISTTOTALSECTION', 
            'FORMLIST.FORMLISTTOTALQUESTION', 'FORMLIST.FORMLISTTITLE'])
        ->joinWith(['formlist'])->where(['FORMID' => $formID])->one()['FORMLISTID'];

        $formlist = Formlist::find()->where(['FORMLISTID' => $formlistID])->one();
        $model = new Country();
        if(isset($formlistID)){
            $modelForm = Form::find()->where(['FORMLISTID' => $formlistID])->one();
            $data = $model->question($formlistID);
            $value = $model->checkbox($formlistID);
        }else{
            return $this->render('error');
        } 
        $modelsFormQuestion = new FormQuestion;
        $modelsFormQuestionOption = new FormQuestionOption;
        $modelsFormAnswerDetail = new FormAnswerDetail();
        
        return $this->render('form',['FormAnswerDetail' => $modelsFormAnswerDetail,'data' => $data,'value' => $value, 'formlist' => $formlist,'formID' =>$formID]);
    }

    public function actionAnswer(){
        $modelFormAnswer = new FormAnswerDetail;
        $model = new Country();
        $data = $model->questionID();
        $answerDetailValue;$idFormAnswer;
        $formID;
        $email = Yii::$app->session->get('email');
        if(isset($_GET['formID'])){
            $formID = $_GET['formID'];
            $formTitle = $_GET['formTitle'];
        }
        //if($modelFormAnswer->load(Yii::$app->request->post())){
            //ini $trash cuman untuk mneampung, sama halnya dengan trash dibawahnya
            $trash = $model->count();
            $trashIDFormAnswer = $model->countFormAnswer();
            // dua foreach ini untuk ambil nilai dan taruh di variable iterasi
            foreach($trash as $tr){
                $count = $tr['SEQ'] + 1;
            }
            foreach($trashIDFormAnswer as $tr){
                $idFormAnswer = $tr['SEQ'] + 1;
            }
            //tmpCount itu untuk nilai sequence yang dipassing ke insertAnswer dan masuk ke table formanswerdetail
            //karena harus unik
            $tmpCount = $count;
            $counter = 0;
            foreach($data as $loop){
                if(isset($_POST[$loop['ID']])){
                $answerDetailValue = $_POST[$loop['ID']];
                // check apakah ada banyak jawaban atau hanya satu jawaban
                    if(is_array($answerDetailValue)){
                        $index = 0 ;
                        foreach($answerDetailValue as $value){
                            // echo $value;
                            if($counter == 0){
                                $model->insertAnswer($tmpCount,$email,$idFormAnswer,$loop['ID'],$value,$formID);
                            }else{
                                $model->insertAnswerDetail($tmpCount,$idFormAnswer,$loop['ID'],$value);
                            }
                            $tmpCount++;$counter++;
                        }
                    }else {
                        if($counter == 0){
                            $model->insertAnswer($tmpCount,$email,$idFormAnswer,$loop['ID'],$answerDetailValue,$formID);
                        }else{
                            $model->insertAnswerDetail($tmpCount,$idFormAnswer,$loop['ID'],$answerDetailValue);
                        }
                        $tmpCount++;$counter++;
                    }
                }
            }
            return $this->render('success', ['formTitle' => $formTitle]);
        //}
    }
    public function actionForms()
    {
        $model = new Answer();
        if($model->load(Yii::$app->request->post()) && $model->save()){
            Yii::$app->session->setFlash('formSubmitted');
            return $this->render('forms',[
                'model' => $model,
            ]);

        }else{
            return $this->render('forms',[
                'model' => $model,
            ]);
        }
    }
}
