<?php

namespace backend\controllers\shop;

use backend\forms\TagsSearch;
use core\entities\shop\Tags;
use core\forms\manage\shop\TagForm;
use core\services\manage\shop\TagsManageService;
use Yii;
use yii\base\Module;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TagsController implements the CRUD actions for Tags model.
 */
class TagsController extends Controller
{
    private $service;

    public function __construct(string $id, Module $module, TagsManageService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->service = $service;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Tags models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TagsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tags model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tags model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new TagForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $tag = $this->service->create($form);
                \Yii::$app->getSession()->setFlash('success', 'Новая метка создана');
                return $this->redirect(['view', 'id' => $tag->id]);

            } catch (\DomainException $e) {
                \Yii::$app->getSession()->setFlash('error', $e->getMessage());
                \Yii::$app->getErrorHandler()->logException($e);
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * Updates an existing Tags model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $form = new TagForm($model);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($id, $form);
                \Yii::$app->getSession()->setFlash('success', 'Метка изменена');
                return $this->redirect(['view', 'id' => $id]);

            } catch (\Exception $e) {
                \Yii::$app->getSession()->setFlash('error', $e->getMessage());
                \Yii::$app->getErrorHandler()->logException($e);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'form' => $form,
        ]);
    }

    /**
     * Deletes an existing Tags model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        try {
            $this->service->remove($id);
            \Yii::$app->getSession()->setFlash('success', 'Метка удалена');
            return $this->redirect(['index']);

        } catch (\Exception $e) {
            \Yii::$app->getSession()->setFlash('error', $e->getMessage());
            \Yii::$app->getErrorHandler()->logException($e);
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Tags model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tags the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tags::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Метка не найдена.');
    }
}
