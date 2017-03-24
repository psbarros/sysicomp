<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjRubricas */

$this->title = $model->codigo.":".$model->nome;//'Update Cont Proj Rubricas: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rubricas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar Informações';
?>
<div class="cont-proj-rubricas-update">

    <!--<h1><?= Html::encode($model->nome) ?></h1>-->
    <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
        ['index'], ['class' => 'btn btn-warning']) ?>
    </br></br>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
