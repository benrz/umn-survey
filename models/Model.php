<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

class Model extends \yii\base\Model
{
    /**
     * Creates and populates a set of models.
     *
     * @param string $modelClass
     * @param array $multipleModels
     * @return array
     */
    public static function createMultiple($modelClass, $multipleModels = [])
    {
        $model    = new $modelClass;
        $formName = $model->formName();
        $post     = Yii::$app->request->post($formName);
        $models   = [];

        if(!empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, 'FORMQUESTIONID', 'FORMQUESTIONID'));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if($post && is_array($post)) {
            foreach ($post as $i => $postItem) {
                if (isset($postItem['FORMQUESTIONID']) && !empty($postItem['FORMQUESTIONID']) && isset($multipleModels[$postItem['FORMQUESTIONID']])) {
                    $models[] = $multipleModels[$postItem['FORMQUESTIONID']];
                } else {
                    $models[] = new $modelClass;
                }
            }
        }

        unset($model, $formName, $post);

        return $models;
    }
}
