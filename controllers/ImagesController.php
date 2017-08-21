<?php

namespace app\controllers;

use app\models\DrawSaveForm;
use Yii;
use app\models\Image;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * ImagesController implements the CRUD actions for Image model.
 */
class ImagesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Image models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Image::find(),
            'sort' => [
                'defaultOrder' => ['image_id' => SORT_DESC],
            ],
        ]);

        return $this->render('gallery', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Image model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Image model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Image();

        $form = new DrawSaveForm();
        $form->initFromImage($model);

        $this->performAjaxValidation($form);

        if ($form->load(Yii::$app->request->post()) && $form->validate() && $form->save($model)) {
            return $this->redirect(['view', 'id' => $model->image_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'drawForm' => $form,
            ]);
        }
    }

    /**
     * Updates an existing Image model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $form = new DrawSaveForm();
        $form->initFromImage($model);

        $this->performAjaxValidation($form);

        if ($form->load(Yii::$app->request->post()) && $form->validate()  && $form->save($model)) {
            return $this->redirect(['view', 'id' => $model->image_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'drawForm' => $form,
            ]);
        }
    }

    protected function performAjaxValidation(DrawSaveForm $model)
    {
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    /**
     * Finds the Image model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Image the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Image::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAuth($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = $this->findModel($id);
        $password = Yii::$app->request->post('password');

        if(!$password){
            throw new BadRequestHttpException('Password required');
        }

        $form = new DrawSaveForm();
        $form->initFromImage($model);

        $form->password = $password;

        if(!$form->validatePassword()) {
            $errors = $form->getErrors('password');
            return [
                'success' => false,
                'message' => array_shift($errors),
            ];
        }else{
            return [
                'success' => true,
            ];
        }

    }
}
