<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model app\models\Afastamentos */

$this->title = "Detalhes do Pedido de Afastamento";
$this->params['breadcrumbs'][] = ['label' => 'Pedidos de Afastamento', 'url' => ['afastamentos/index']];
$this->params['breadcrumbs'][] = $this->title;

$tipoViagem = array(null => " <div style=\"color:red; font-weight:bold\"> Não definido <div>" , 1 => "Nacional", 1 => "Internacional");

?>
<div class="afastamentos-view">

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['afastamentos/index'], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir Solcitação de Afastamento  ', ['afastamentos/print', 'id' => $model->id], [
                            'target' => '_blank', 'class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',           
            [
                'attribute' => 'dataenvio',
                'format'=>'raw',
                'value' => date("d/m/Y", strtotime($model->dataenvio)).' às '.date("H:i:s", strtotime($model->dataenvio))
            ],
            'nomeusuario',          
            'local',                        
            [
                'attribute' => 'tipo',
                'format'=>'raw',
                'value' => $model->tipo == 1 ? 'Nacional' : 'Internacional'
            ],
            [
                'attribute' => 'datasaida',
                'format'=>'raw',
                'value' => date("d/m/Y", strtotime($model->datasaida))
            ],
            [
                'attribute' => 'dataretorno',
                'format'=>'raw',
                'value' => date("d/m/Y", strtotime($model->dataretorno))
            ],
            'justificativa',
            'reposicao',

        ],
    ]) ?>

</div>
