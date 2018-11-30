<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\TagsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Метки';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tags-index">
    <p>
        <?= Html::a('Создать метку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    'name',
                    'slug',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>

        </div>
    </div>
</div>
