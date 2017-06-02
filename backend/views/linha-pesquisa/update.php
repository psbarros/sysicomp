<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LinhaPesquisa */

$this->title =  "Editar Linha de Pesquisa " . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Linha Pesquisas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nome, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="linha-pesquisa-update">
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;&nbsp;Voltar','#',['class' => 'btn btn-warning','onclick'=>"history.go(-1);"]); ?>
        <?= Html::a('<span class="fa fa-list"></span>&nbsp;&nbsp;Listar Linhas de Pesquisa', ['linha-pesquisa/index'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
