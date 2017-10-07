<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AgendarDefesa */

$this->title = 'Update Agendar Defesa: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Agendar Defesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idDefesa, 'url' => ['view', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="agendar-defesa-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
