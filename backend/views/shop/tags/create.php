<?php

/* @var $this yii\web\View */
/* @var $model core\entities\shop\Tags */

$this->title = 'Создать новую метку';
$this->params['breadcrumbs'][] = ['label' => 'Метки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tags-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
