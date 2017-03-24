<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjBancos */

$this->title = $model->nome;//'Update Cont Proj Bancos: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Bancos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar Informações';
?>
<div class="cont-proj-bancos-update">

    <!--<h1><?= Html::encode($model->nome) ?></h1>-->
    <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
        ['index'], ['class' => 'btn btn-warning']) ?>
    </br></br>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
