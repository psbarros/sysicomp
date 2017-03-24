<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjRubricasdeProjetos */
$idProjeto = Yii::$app->request->get('idProjeto');
$modelProjeto = \backend\models\ContProjProjetos::find()->where("id=$idProjeto")->one();
$coordenador = \app\models\User::find()->select("*")->where("id=$modelProjeto->coordenador_id")->one();

$this->title = mb_strimwidth("Rubricas do projeto - ".$modelProjeto->nomeprojeto,0,60,"...");
$this->params['breadcrumbs'][] = ['label' => "$modelProjeto->nomeprojeto", 'url' => ['index', 'idProjeto'=>$idProjeto]];
$this->params['breadcrumbs'][] = 'Atualizar Dados';
?>
<div class="cont-proj-rubricasde-projetos-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
        ['index', 'idProjeto'=>$idProjeto], ['class' => 'btn btn-warning']) ?>
    </br></br>

    <?= $this->render('..\cont-proj-projetos\dados.php', [
        'idProjeto' => $idProjeto,
    ]) ?>

    <?= $this->render('_form', [
        'model' => $model,
        'rubricas'=>$rubricas,
        'update' => true,
    ]) ?>


</div>
