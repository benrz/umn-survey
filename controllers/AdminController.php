<?php

namespace app\controllers;

use Yii;
use app\models\FormList;
use app\models\FormListSearch;
use app\models\Form;
use app\models\FormSearch;
use app\models\ResultSearch;
use app\models\FormQuestion;
use app\models\Model;
use app\models\FormAnswer;
use app\models\FormAnswerSearch;
use app\models\FormAnswerDetail;
use app\models\FormQuestionOption;
use app\models\FormPublish;
use app\models\SendFrom;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;

/**
 * AdminController implements the CRUD actions for FormList model.
 */
class AdminController extends Controller
{
    public $layout = 'admin';
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all FormList models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FormSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // // Function untuk automatic close form apabila dateNow > dateEnd
        // $forms = Form::find()->where(['FORMSTATUS' => 1])->all();
        // foreach($forms as $form){
        //     if(strtotime($form->FORMDATEEND) < strtoupper(strtotime(date('d-M-y')))){
        //         echo "This Form: $form->FORMID is deprecated<br>";
        //     }
        // }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTemplate()
    {
        $searchModel = new FormListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('template', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays a single FormList model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'modelFormList' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new FormList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $modelFormList = new FormList;
        $modelForm = new Form;
        $modelFormList->FORMLISTDATE = date('d-M-y');
        $modelsFormQuestion = [new FormQuestion];
        $modelsFormQuestionOption = [[new FormQuestionOption]];
        
        if ($modelFormList->load(Yii::$app->request->post())) {
            $modelFormList->FORMLISTTOTALSECTION = 0;
            $modelFormList->FORMLISTTOTALQUESTION = 0;

            $modelsFormQuestion = Model::createMultiple(FormQuestion::classname());
            Model::loadMultiple($modelsFormQuestion, Yii::$app->request->post());
            
            $valid = $modelFormList->validate();
            $valid = Model::validateMultiple($modelsFormQuestion) && $valid;

            if (isset($_POST['FormQuestionOption'][0][0])) {
                foreach ($_POST['FormQuestionOption'] as $indexFormList => $formQuestionOptions) {
                    $modelFormList->FORMLISTTOTALQUESTION += 1;
                    $modelsFormQuestion[$indexFormList]->FORMQUESTIONPOSITION = $indexFormList;
                    $modelsFormQuestion[$indexFormList]->FORMQUESTIONSECTION = 0;
                    $modelsFormQuestion[$indexFormList]->FORMIMAGE = UploadedFile::getInstance($modelsFormQuestion[$indexFormList], "[{$indexFormList}]FORMIMAGE");
                    $modelsFormQuestion[$indexFormList]->upload();

                    foreach ($formQuestionOptions as $indexFormQuestionOption => $formQuestionOption) {
                        $data['FormQuestionOption'] = $formQuestionOption;
                        $modelFormQuestionOption = new FormQuestionOption;

                        $modelFormQuestionOption->load($data);
                        $modelFormQuestionOption->FORMQUESTIONOPTIONROWPOSITION = $indexFormQuestionOption;

                        $modelsFormQuestionOption[$indexFormList][$indexFormQuestionOption] = $modelFormQuestionOption;
                        $valid = $modelFormQuestionOption->validate();
                    }
                }
            }

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                $modelFormList->save(false);
                $transaction->commit();
                $modelFormList->FORMLISTID = FormList::find()->select('FORMLISTID')->max('FORMLISTID');

                $transaction = Yii::$app->db->beginTransaction();
                $modelForm->FORMLISTID = $modelFormList->FORMLISTID;
                $modelForm->FORMDATESTART = date('d-M-y');
                $modelForm->FORMDATEEND = date('d-M-y');
                $modelForm->USERJOBID = 3;
                $modelForm->FORMSTATUS = 1;
                $modelForm->save();
                $transaction->commit();
                
                try {
                    if ($flag = $modelFormList->save(false)) {
                        foreach ($modelsFormQuestion as $indexFormList => $modelFormQuestion) {
                            if ($flag === false) {
                                break;
                            }
                            
                            $transaction = Yii::$app->db->beginTransaction();
                            $modelFormQuestion->FORMLISTID = $modelFormList->FORMLISTID;
                            $modelFormQuestion->save(false);
                            if (!($flag = $modelFormQuestion->save(false))) {
                                break;
                            }
                            $transaction->commit();
                            $modelFormQuestion->FORMQUESTIONID = FormQuestion::find()->select('FORMQUESTIONID')->max('FORMQUESTIONID');

                            if (isset($modelsFormQuestionOption[$indexFormList]) && is_array($modelsFormQuestionOption[$indexFormList])) {
                                foreach ($modelsFormQuestionOption[$indexFormList] as $indexFormQuestionOption => $modelFormQuestionOption) {
                                    $transaction = Yii::$app->db->beginTransaction();
                                    $modelFormQuestionOption->FORMQUESTIONID = $modelFormQuestion->FORMQUESTIONID;
                                    $modelFormQuestionOption->save(false);
                                    if (!($flag = $modelFormQuestionOption->save(false))) {
                                        break;
                                    }
                                    $transaction->commit();
                                }
                            }
                        }
                    }

                    if ($flag) {
                        return $this->redirect(['view', 'id' => $modelFormList->FORMLISTID]);
                    } else {
                        $transaction->rollBack();
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }

            return; // debug
        }

        return $this->render('create', [
            'modelFormList' => $modelFormList,
            'modelsFormQuestion' => (empty($modelsFormQuestion)) ? [new FormQuestion] : $modelsFormQuestion,
            'modelsFormQuestionOption' => (empty($modelsFormQuestionOption)) ? [[new FormQuestionOption]] : $modelsFormQuestionOption,
        ]);
    }

    public function actionAdd($id){
        $modelForm = new Form;
        $modelFormList = (new \yii\db\Query())
            ->from('FORMLIST')
            ->where(['FORMLISTID' => $id])->one();
        
        $transaction = Yii::$app->db->beginTransaction();
        $modelForm->FORMLISTID = $modelFormList['FORMLISTID'];
        $modelForm->FORMDATESTART = date('d-M-y');
        $modelForm->FORMDATEEND = date('d-M-y');
        $modelForm->USERJOBID = 3;
        $modelForm->FORMSTATUS = 1;
        
        $modelForm->save();
        $transaction->commit();
        echo "<script>alert('Template $modelFormList[FORMLISTTITLE] has been used.');</script>";

        return $this->redirect(['admin/index']);
    }

    /**
     * Updates an existing FormList model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {            
        $modelFormList = $this->findModel($id);
        $modelsFormQuestion = FormQuestion::find()->where(['FORMLISTID' => $modelFormList->FORMLISTID])->orderBy(['FORMQUESTIONPOSITION' => SORT_ASC])->all();
        $modelsFormQuestionOption = [];
        $oldFormQuestionOption = [];
        
        if (!empty($modelsFormQuestion)) {
            foreach ($modelsFormQuestion as $indexFormList => $modelFormQuestion) {
                $formQuestionOptions = FormQuestionOption::find()->where(['FORMQUESTIONID' => $modelFormQuestion->FORMQUESTIONID])->orderBy(['FORMQUESTIONOPTIONROWPOSITION' => SORT_ASC])->all();
                $modelsFormQuestionOption[$indexFormList] = $formQuestionOptions;
                $oldFormQuestionOption = ArrayHelper::merge(ArrayHelper::index($formQuestionOptions, 'FORMQUESTIONOPTIONID'), $oldFormQuestionOption);
            }
        }
        

        if ($modelFormList->load(Yii::$app->request->post())) {
            $modelFormList->FORMLISTDATE = date('d-M-y');
            $modelFormList->FORMLISTTOTALSECTION = 0;
            $modelFormList->FORMLISTTOTALQUESTION = 0;
            $modelsFormQuestionOption = [];
            $oldFormQuestionID = ArrayHelper::map($modelsFormQuestion, 'FORMQUESTIONID', 'FORMQUESTIONID');

            $modelsFormQuestion = Model::createMultiple(FormQuestion::classname(), $modelsFormQuestion);
            Model::loadMultiple($modelsFormQuestion, Yii::$app->request->post());
            $deletedFormQuestionID = array_diff($oldFormQuestionID, array_filter(ArrayHelper::map($modelsFormQuestion, 'FORMQUESTIONID', 'FORMQUESTIONID')));

            $valid = $modelFormList->validate();
            $valid = Model::validateMultiple($modelsFormQuestion) && $valid;

            $formQuestionOptionID = [];
            if (isset($_POST['FormQuestionOption'][0][0])) {
                foreach ($_POST['FormQuestionOption'] as $indexFormList => $formQuestionOptions) {
                    $formQuestionOptionID = ArrayHelper::merge($formQuestionOptionID, array_filter(ArrayHelper::getColumn($formQuestionOptions, 'FORMQUESTIONOPTIONID')));
                    $modelFormList->FORMLISTTOTALQUESTION += 1;
                    $modelsFormQuestion[$indexFormList]->FORMQUESTIONPOSITION = $indexFormList;
                    $modelsFormQuestion[$indexFormList]->FORMQUESTIONSECTION = 0;

                    foreach ($formQuestionOptions as $indexFormQuestionOption => $formQuestionOption) {
                        $data['FormQuestionOption'] = $formQuestionOption;
                        $modelFormQuestionOption = (isset($formQuestionOption['FORMQUESTIONOPTIONID']) && isset($oldFormQuestionOption[$formQuestionOption['FORMQUESTIONOPTIONID']])) ? $oldFormQuestionOption[$formQuestionOption['FORMQUESTIONOPTIONID']] : new FormQuestionOption;
                        
                        $modelFormQuestionOption->load($data);
                        $modelFormQuestionOption->FORMQUESTIONOPTIONROWPOSITION = $indexFormQuestionOption;
                        
                        $modelsFormQuestionOption[$indexFormList][$indexFormQuestionOption] = $modelFormQuestionOption;
                        $valid = $modelFormQuestionOption->validate();
                    }
                }
            }

            $oldFormQuestionOptionID = ArrayHelper::getColumn($oldFormQuestionOption, 'FORMQUESTIONOPTIONID');
            $deletedFormQuestionOptionID = array_diff($oldFormQuestionOptionID, $formQuestionOptionID);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                $modelFormList->save(false);
                $transaction->commit();

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $modelFormList->save(false)) {
                        if (! empty($deletedFormQuestionOptionID)) {
                            FormQuestionOption::deleteAll(['FORMQUESTIONOPTIONID' => $deletedFormQuestionOptionID]);
                        }

                        if (! empty($deletedFormQuestionID)) {
                            FormQuestion::deleteAll(['FORMQUESTIONID' => $deletedFormQuestionID]);
                        }

                        foreach ($modelsFormQuestion as $indexFormList => $modelFormQuestion) {
                            if ($flag === false) {
                                break;
                            }

                            $transaction = Yii::$app->db->beginTransaction();
                            if ($modelFormQuestion->FORMQUESTIONID == NULL) {
                                $modelFormQuestion->FORMQUESTIONID = FormQuestion::find()->select('FORMQUESTIONID')->max('FORMQUESTIONID') + 1;
                            }
                            $modelFormQuestion->FORMLISTID = $modelFormList->FORMLISTID;
                            $modelFormQuestion->save(false);
                            if (!($flag = $modelFormQuestion->save(false))) {
                                break;
                            }
                            $transaction->commit();

                            if (isset($modelsFormQuestionOption[$indexFormList]) && is_array($modelsFormQuestionOption[$indexFormList])) {
                                foreach ($modelsFormQuestionOption[$indexFormList] as $indexFormQuestionOption => $modelFormQuestionOption) {
                                    $transaction = Yii::$app->db->beginTransaction();
                                    if ($modelFormQuestionOption->FORMQUESTIONOPTIONID == NULL) {
                                        $modelFormQuestionOption->FORMQUESTIONOPTIONID = FormQuestionOption::find()->select('FORMQUESTIONOPTIONID')->max('FORMQUESTIONOPTIONID') + 1;
                                    }
                                    $modelFormQuestionOption->FORMQUESTIONID = $modelFormQuestion->FORMQUESTIONID;
                                    $modelFormQuestionOption->save(false);
                                    if (!($flag = $modelFormQuestionOption->save(false))) {
                                        break;
                                    }
                                    $transaction->commit();
                                }
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $modelFormList->FORMLISTID]);
                    } else {
                        $transaction->rollBack();
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }

            return; // debug
        }

        return $this->render('update', [
            'modelFormList' => $modelFormList,
            'modelsFormQuestion' => (empty($modelsFormQuestion)) ? [new FormQuestion] : $modelsFormQuestion,
            'modelsFormQuestionOption' => (empty($modelsFormQuestionOption)) ? [[new FormQuestionOption]] : $modelsFormQuestionOption,
        ]);
    }


    /**
     * Deletes an existing FormList model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $formAnswerId = FormAnswer::find()
                        ->select(['FORMANSWERID'])
                        ->where(['FORMID' => $id])
                        ->all();
        if($formAnswerId != null){
            foreach($formAnswerId as $answerId){
                FormAnswerDetail::deleteAll(['FORMANSWERID' => $answerId['FORMANSWERID']]);
            }
            FormAnswer::deleteAll(['FORMID' => $id]);
        }
        
        FormPublish::deleteAll(['FORMID' => $id]);
        Form::deleteAll(['FORMID' => $id]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the FormList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return FormList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FormList::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionResult()
    {
        $searchModel = new ResultSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('result', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAnswer($id)
    {
        $searchModel = new FormAnswerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // $dataProvider = new ActiveDataProvider([
        //     'query' => $modelFormAnswer::find(),
        //     'pagination' => [
        //         'pageSize' => 20,
        //     ],
        // ]);

        $formListID = (new \yii\db\Query())
        ->select(['FORM.FORMLISTID'])
        ->from('FORMANSWER')
        ->innerJoin('FORM', 'FORM.FORMID = FORMANSWER.FORMID')
        ->where(['FORMANSWER.FORMID' => $id])->one();

        $totalForm = Form::find()
        ->where(['FORMLISTID' => $formListID])
        ->count();

        // $formListID = $formListID["FORMLISTID"];
        // echo "<pre>";
        // print_r($formListID);
        // echo "</pre>";
        
        $formQuestions = (new \yii\db\Query())
        ->select(['FORMQUESTIONNAME', 'FORMQUESTIONTYPEID', 'FORMQUESTIONID'])
        ->from('FORMLIST')
        ->innerJoin('FORMQUESTION', 'FORMQUESTION.FORMLISTID = FORMLIST.FORMLISTID')
        ->where(['FORMQUESTION.FORMLISTID' => $formListID])->all();
        

        $rows = (new \yii\db\Query())
            ->select(['FORMANSWER.USEREMAIL', 'FORMQUESTIONNAME', 'FORMANSWERDETAIL.FORMANSWERDETAILVALUE'])
            ->from('FORMANSWER')
            ->innerJoin('FORMANSWERDETAIL', 'FORMANSWERDETAIL.FORMANSWERID = FORMANSWER.FORMANSWERID')
            ->innerJoin('FORMQUESTION', 'FORMANSWERDETAIL.FORMQUESTIONID = FORMQUESTION.FORMQUESTIONID')
            ->where(['FORMANSWER.FORMID' => $id])
            ->orderBy('FORMANSWERDETAIL.FORMQUESTIONID')
            ->all();

        $answers = array();
        $formQuestionData = array();

        foreach($rows as $row){
            if(empty($answers["$row[USEREMAIL]"])) {
                $answers["$row[USEREMAIL]"] = array();
                // $answers["$row[USEREMAIL]"]["FORMQUESTIONNAME"] = array();
                // $answers["$row[USEREMAIL]"]["FORMANSWERDETAILVALUE"] = array();
            } 
            if(empty($answers["$row[USEREMAIL]"]["$row[FORMQUESTIONNAME]"])){
                $answers["$row[USEREMAIL]"]["$row[FORMQUESTIONNAME]"] = $row["FORMANSWERDETAILVALUE"];
            }else{
                $answers["$row[USEREMAIL]"]["$row[FORMQUESTIONNAME]"] = $answers["$row[USEREMAIL]"]["$row[FORMQUESTIONNAME]"].", ".$row["FORMANSWERDETAILVALUE"];
            }
            // array_push($answers["$row[USEREMAIL]"]["FORMANSWERDETAILVALUE"], $row["FORMANSWERDETAILVALUE"]);
        }

        foreach($formQuestions as $formQuestion){
            $formQuestionData["$formQuestion[FORMQUESTIONID]"] = array();
            $formQuestionData["$formQuestion[FORMQUESTIONID]"]["$formQuestion[FORMQUESTIONNAME]"] = $formQuestion["FORMQUESTIONTYPEID"];
            
        }

        // $formQuestionNames = array_unique($formQuestionNames);

        return $this->render('answer', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'answers' => $answers,
            'formQuestionData' => $formQuestionData,
            'id' => $id,
            'totalForm' => $totalForm,
        ]);
    }

    public function actionExcel($id) {
        $searchModel = new FormAnswerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $rows = (new \yii\db\Query())
            ->select(['FORMANSWER.USEREMAIL', 'FORMQUESTION.FORMQUESTIONNAME', 'FORMANSWERDETAIL.FORMANSWERDETAILVALUE'])
            ->from('FORMANSWER')
            ->innerJoin('FORMANSWERDETAIL', 'FORMANSWERDETAIL.FORMANSWERID = FORMANSWER.FORMANSWERID')
            ->innerJoin('FORMQUESTION', 'FORMANSWERDETAIL.FORMQUESTIONID = FORMQUESTION.FORMQUESTIONID')
            ->where(['FORMANSWER.FORMID' => $id])
            ->all();

        $answers = array();
        $formQuestionNames = array();
        foreach($rows as $row){
            if(empty($answers["$row[USEREMAIL]"])) {
                $answers["$row[USEREMAIL]"] = array();
                $answers["$row[USEREMAIL]"]["FORMQUESTIONNAME"] = array();
                $answers["$row[USEREMAIL]"]["FORMANSWERDETAILVALUE"] = array();
            } 
            array_push($formQuestionNames, $row["FORMQUESTIONNAME"]);
            array_push($answers["$row[USEREMAIL]"]["FORMANSWERDETAILVALUE"], $row["FORMANSWERDETAILVALUE"]);
        }

        $formQuestionNames = array_unique($formQuestionNames);

        return $this->renderPartial('excel', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'answers' => $answers,
            'formQuestionNames' => $formQuestionNames,
        ]);
    }
    /**
     * Lists all FormList models.
     * @return mixed
     */
    public function actionSpread()
    {
        $searchModel = new FormSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('spread', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays send page.
     *
     * @return Response|string
     */    
    public function actionSend($id)
    {
        $model = new SendFrom();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->db->createCommand()->update('FORM', ['PUBLICS' => 0, 'LECTURER' => 0, 'STUDENT' => 0, 'STAFF' => 0], 'FORMID = '.$id)->execute();
            foreach(Yii::$app->request->post()['SendFrom']['user'] as $target){
                if($target == 1){
                    Yii::$app->db->createCommand()->update('FORM', ['PUBLICS' => 1], 'FORMID = '.$id)->execute();
                }
                else if($target == 2){
                    Yii::$app->db->createCommand()->update('FORM', ['LECTURER' => 1], 'FORMID = '.$id)->execute();
                }
                else if($target == 3){
                    Yii::$app->db->createCommand()->update('FORM', ['STUDENT' => 1], 'FORMID = '.$id)->execute();
                }
                else if($target == 4){
                    Yii::$app->db->createCommand()->update('FORM', ['STAFF' => 1], 'FORMID = '.$id)->execute();
                }
            }
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }else
        return $this->render('send', [
            'model' => $model,
            'id' => $id,
        ]);
    }

    // Menampilkan Chart
    public function actionChart($formQuestionID, $formID){
        $modelFormPublish = new FormPublish(); 
        $formQuestion = FormQuestion::findOne($formQuestionID); // Untuk dapetin keseluruhan informasi formQuestion
        
        $formQuestionOption = FormQuestionOption::find()
                                ->where(['FORMQUESTIONID' => $formQuestionID])->all(); // Untuk dapeti semua formQuestionOption
                                
        $isPublished = FormPublish::find()   
        ->where([
            'FORMQUESTIONID' => $formQuestionID,
            'FORMID' => $formID,
            'TREND' => 0])->one();

        if ($modelFormPublish->load(Yii::$app->request->post())){
            if(!empty($modelFormPublish->LECTURER) || !empty($modelFormPublish->PUBLICS) || !empty($modelFormPublish->STAFF) || !empty($modelFormPublish->STUDENT)){
                if($isPublished == NULL){
                    $transaction = Yii::$app->db->beginTransaction();
                    $modelFormPublish->save();
                    $transaction->commit();
                }
                else{                    
                    $transaction = Yii::$app->db->beginTransaction();
                    Yii::$app->db->createCommand()->update('FORMPUBLISH', 
                    [
                        'LECTURER' => $modelFormPublish->LECTURER,
                        'PUBLICS' => $modelFormPublish->PUBLICS,
                        'STAFF' => $modelFormPublish->STAFF,
                        'STUDENT' => $modelFormPublish->STUDENT,

                    ], 
                    [
                        'FORMID' => $modelFormPublish->FORMID,
                        'FORMQUESTIONID' => $modelFormPublish->FORMQUESTIONID,
                    ])->execute();
                    $transaction->commit();
                }

                // Pindah controller
                // return Yii::$app->runAction('site/index');
                // return Url::to(['site/index']);
                // return $this->redirect(['site/index']);
                
            }
            else{
                echo "<script>alert('Please select at least one of the option')</script>";
            }
        }

        // echo "<pre>";
        // print_r($formQuestionOption) ;
        // echo "</pre>";
        
        // $countArray untuk bikin associative array buat nampung $formOption->FORMQUESTIONVALUE = count
        // Misal: 
        //     Pilihan1 => 2,
        //     Pilihan2 => 3,
        $countArray = []; 
        foreach($formQuestionOption as $formOption){
            $countArray["$formOption->FORMQUESTIONVALUE"] = 0; // Awalnya diinisialisasi 0 semua
        }
        
        if($formQuestion->FORMQUESTIONTYPEID == 6){
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

        return $this->render('chart', [
            'formQuestion' => $formQuestion, // Untuk dapetin keseluruhan informasi formQuestion
            'countArray' => $countArray, // Associative array yang digunakan buat menampung nilai untuk sumbu X => nilai untuk sumbu Y
            'formQuestionOption' => $formQuestionOption,
            'formID' => $formID,
            'modelFormPublish' => $modelFormPublish,
            'isPublished' => $isPublished,
            // Untuk nentui bentuk grafik panggil: $formQuestion->FORMQUESTIONTYPEID
            // Untuk Legend panggil: $formQuestion->FORMQUESTIONNAME

            // Kalau mau akses associative array di view, pake (perlu didalam php):
            // $keys = array_keys( $countArray ); 
            // for($x = 0; $x < sizeof($keys); $x++ ) { 
            //     echo "key: ". $keys[$x] . ", value: " 
            //             . $countArray[$keys[$x]] . "\n"; 
            //     // note:   $keys[$x] untuk generate optionValue (nilai untuk sumbu X)
            //     //         $countArray[$keys[$x]] untuk generate COUNT (jumlah orang yg pilih) optionValue tsb (nilai untuk sumbu Y)
            // } 
        ]);
    }

    public function actionTrend($formQuestionID, $formID){
        $modelFormPublish = new FormPublish(); 
        $formQuestion = FormQuestion::findOne($formQuestionID); // Untuk dapetin keseluruhan informasi formQuestion
        
        $formQuestionOption = FormQuestionOption::find()
                                ->where(['FORMQUESTIONID' => $formQuestionID])->all(); // Untuk dapeti semua formQuestionOption
                         
        $isPublished = FormPublish::find()   
        ->where([
            'FORMQUESTIONID' => $formQuestionID,
            // 'FORMID' => $formID,
            'TREND' => 1])->one();

        if ($modelFormPublish->load(Yii::$app->request->post())){
            if(!empty($modelFormPublish->LECTURER) || !empty($modelFormPublish->PUBLICS) || !empty($modelFormPublish->STAFF) || !empty($modelFormPublish->STUDENT)){
                if($isPublished == NULL){
                    $transaction = Yii::$app->db->beginTransaction();
                    $modelFormPublish->TREND = 1;
                    $modelFormPublish->save();
                    $transaction->commit();
                }
                else{                    
                    $transaction = Yii::$app->db->beginTransaction();
                    Yii::$app->db->createCommand()->update('FORMPUBLISH', 
                    [
                        'LECTURER' => $modelFormPublish->LECTURER,
                        'PUBLICS' => $modelFormPublish->PUBLICS,
                        'STAFF' => $modelFormPublish->STAFF,
                        'STUDENT' => $modelFormPublish->STUDENT,

                    ], 
                    [
                        'FORMQUESTIONID' => $modelFormPublish->FORMQUESTIONID,
                    ],)->execute();
                    $transaction->commit();
                }

                // Pindah controller
                // return Yii::$app->runAction('site/index');
                // return Url::to(['site/index']);
                // return $this->redirect(['site/index']);
                
            }
            else{
                echo "<script>alert('Please select at least one of the option')</script>";
            }
        }

        $countArray = []; 
        $months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
        
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

        // echo "<pre>";
        // print_r($keys);
        // print_r($month_keys);
        // print_r($countArray);
        // echo "</pre>";
        return $this->render('trend', [
            'formQuestion' => $formQuestion,
            'countArray' => $countArray,
            'keys' => $keys,
            'month_keys' => $month_keys,
            'formQuestionOption' => $formQuestionOption,
            'formID' => $formID,
            'modelFormPublish' => $modelFormPublish,
            'isPublished' => $isPublished,
        ]);
    }
    
    public function actionStatus($id)
    {
        $query = new \yii\db\Query;
        $query
            ->select('FORMSTATUS')
            ->from('FORM')
            ->where(['FORMID'=>$id]);
        $command = $query->createCommand();
        $status = $command->queryOne();
        if($status['FORMSTATUS'] == 1){
            Yii::$app->db->createCommand()->update('FORM', ['FORMSTATUS' => 0], 'FORMID = '.$id)
                ->execute();
            
            Yii::$app->db->createCommand()->update('FORM', ['FORMDATEEND' => date('d-M-y')], 'FORMID = '.$id)
                ->execute();    
        }else{
            Yii::$app->db->createCommand()->update('FORM', ['FORMSTATUS' => 1], 'FORMID = '.$id)
                ->execute();
        }    
        return $this->redirect(['admin/spread']);
    }
}
