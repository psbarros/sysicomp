<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\BaixaCautela */

$this->title = "Baixa da Cautela: #".$model->idBaixaCautela;
$this->params['breadcrumbs'][] = ['label' => 'Baixa Cautelas', 'url' => ['cautela/index']];
$this->params['breadcrumbs'][] = '#'.$model->idBaixaCautela;
?>
<div class="baixa-cautela-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	<?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['cautela/view', 'id'=>$model->idCautela], ['class' => 'btn btn-warning']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idBaixaCautela',
            'idCautela',
            //'idCautelaAvulsa',
            'Recebedor',
            'DataDevolucao',
            //'Equipamento',
            'ObservacaoBaixaCautela',
        ],
    ]) ?>

</div>
