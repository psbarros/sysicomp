<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ContProjRegistraDatas */
$idProjeto = Yii::$app->request->get('idProjeto');
$this->title = 'Registrar Nova Data';
$this->params['breadcrumbs'][] = ['label' => 'Registrar Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-registra-datas-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['index', 'idProjeto' => $idProjeto], ['class' => 'btn btn-warning']) ?>
    </p>

    <?= $this->render('_form', [
        'model' => $model,
        'projetos'=>$projetos,
        'idProjeto' =>$idProjeto,
    ]) ?>

</div>
