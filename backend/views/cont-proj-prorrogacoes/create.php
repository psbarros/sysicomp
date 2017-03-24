<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ContProjProrrogacoes */

$idProjeto = Yii::$app->request->get('idProjeto');

$this->title = 'Prorrogar data final do projeto';
$this->params['breadcrumbs'][] = ['label' => 'Prorrogacoes', 'url' => ['index','idProjeto'=>$idProjeto]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-prorrogacoes-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['index', 'idProjeto' => $idProjeto], ['class' => 'btn btn-warning']) ?>
    </p>

    <?= $this->render('..\cont-proj-projetos\dados', [
        'idProjeto' => $idProjeto,
    ]) ?>

    <?= $this->render('_form', [
        'model' => $model,
        'idProjeto'=>$idProjeto,
    ]) ?>

</div>
