<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ContProjProjetos */

$this->title = 'Projetos de Pesquisa e Desenvolvimento';
$this->params['breadcrumbs'][] = ['label' => 'Projetos de Pesquisa e Desenvolvimento', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-projetos-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
        ['index'], ['class' => 'btn btn-warning']) ?>


    <?= $this->render('_form', [
        'model' => $model,
        'coordenadores' => $coordenadores,
        'agencias' => $agencias,
        'bancos' => $bancos,
    ]) ?>
</div>
