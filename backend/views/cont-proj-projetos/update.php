<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjProjetos */


$this->title = $model->nomeprojeto;
$this->params['breadcrumbs'][] = ['label' => 'Projetos de Pesquisa e desenvolvimento', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nomeprojeto, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cont-proj-projetos-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'coordenadores' => $coordenadores,
        'agencias' => $agencias,
        'bancos' => $bancos,
    ]) ?>

</div>
