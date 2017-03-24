<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BancaControleDefesas */

$this->title = "Justificativa do Indeferimento da Banca";
$this->params['breadcrumbs'][] = ['label' => 'Banca Controle Defesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="banca-controle-defesas-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
