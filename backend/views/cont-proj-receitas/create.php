<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ContProjReceitas */

$idProjeto = Yii::$app->request->get('idProjeto');

$this->title = 'Cadastrar Nova Receita';
$this->params['breadcrumbs'][] = ['label' => 'Receitas', 'url' => ['index', 'idProjeto' => $idProjeto]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-receitas-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['index', 'idProjeto' => $idProjeto], ['class' => 'btn btn-warning']) ?>
    </p>
    <?= $this->render('..\cont-proj-projetos\dados.php', [
        'idProjeto' => $idProjeto,
    ]) ?>

    <?= $this->render('_form', [
        'rubricasdeProjeto' => $rubricasdeProjeto,
        'model' => $model,
        'idProjeto' => $idProjeto,
    ]) ?>

</div>
