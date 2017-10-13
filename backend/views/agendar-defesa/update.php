<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AgendarDefesa */

$this->title = $model->titulo;
$this->params['breadcrumbs'][] = ['label' => 'Agendar Defesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->titulo, 'url' => ['view', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="agendar-defesa-update">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
