<?php

/* @var $this yii\web\View */
/* @var $model core\entities\shop\Tags */
/* @var $form \core\forms\manage\shop\TagForm */

$this->title = 'Изменить метку: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Метки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="tags-update">

    <?= $this->render('_form', [
        'model' => $form,
    ]) ?>

</div>
