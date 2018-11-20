<?php

use core\helpers\WidgetHelpers;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;

$datePickerCreated = WidgetHelpers::DatePickerRange($searchModel, 'created_from', 'created_to');
$datePickerUpdated = WidgetHelpers::DatePickerRange($searchModel, 'updated_from', 'updated_to');
?>

<div class="user-index">

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    'username',
                    'email:email',
                    [
                        'attribute' => 'status',
                        'value' => function (\core\entities\User\User $user) {
                            return \core\helpers\UserHelpers::statusTag($user->status);
                        },
                        'filter' => \core\helpers\UserHelpers::statusList(),
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'created_at',
                        'filter' => $datePickerCreated,
                        'format' => 'datetime'
                    ],
                    [
                        'attribute' => 'updated_at',
                        'filter' => $datePickerUpdated,
                        'format' => 'datetime'
                    ],

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>

        </div>
    </div>

</div>
