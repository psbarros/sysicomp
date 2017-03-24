<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Prorrogacao */

$this->title = 'Editar Prorrogação';
$this->params['breadcrumbs'][] = ['label' => 'Gerenciar Prorrogações', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Visualizar Prorrogação', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="prorrogacao-update">

    <!--h1><?= Html::encode($this->title) ?></h1-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
