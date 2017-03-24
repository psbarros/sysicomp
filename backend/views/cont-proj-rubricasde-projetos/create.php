<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ContProjRubricasdeProjetos */


$idProjeto = Yii::$app->request->get('idProjeto');
$modelProjeto = \backend\models\ContProjProjetos::find()->where("id=$idProjeto")->one();

$this->title = $modelProjeto->nomeprojeto;

$this->params['breadcrumbs'][] = ['label' => "$modelProjeto->nomeprojeto", 'url' => ['index','idProjeto'=>$idProjeto]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-rubricasde-projetos-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
        ['index', 'idProjeto'=>$idProjeto], ['class' => 'btn btn-warning']) ?>

    </br></br>
    <?= $this->render('..\cont-proj-projetos\dados.php', [
        'idProjeto' => $idProjeto,
    ]) ?>

    <?= $this->render('_form', [
        'idProjeto'=>$idProjeto,
        'model' => $model,
        'rubricas' => $rubricas,
    ]) ?>



</div>
