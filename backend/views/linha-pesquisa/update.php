<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LinhaPesquisa */

$this->title =  "Alterar: 	".$model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Linha Pesquisas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nome, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Alterar';
?>
<div class="linha-pesquisa-update">
<p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['view', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
    </p>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
