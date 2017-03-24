<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use xj\bootbox\BootboxAsset;

BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);

$this->title = "Reserva: ".$model->atividade. " para ".date("d-m-Y", strtotime($model->dataInicio)). " - ".$model->horaInicio;
$this->params['breadcrumbs'][] = ['label' => 'Reserva Salas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->salaDesc->nome, 'url' => ['calendario', 'idSala' => $model->sala]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reserva-sala-view">

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-calendar"></span> Calendário de Reservas', ['calendario', 'idSala' => $model->sala], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-list"></span> Listagem de Reservas', ['listagemreservas'], ['class' => 'btn btn-warning']) ?>
        <?php if(($model->idSolicitante == Yii::$app->user->identity->id || Yii::$app->user->identity->checarAcesso('secretaria')) && $model->dataInicio >= date('Y-m-d') || ($model->dataInicio == date('Y-m-d') && 
            $model->horaInicio > date('H:i:s'))){ ?>
                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Alterar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-remove"></span> Remover', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Deseja desmarcar a reserva \''.$model->atividade.'\'?',
                        'method' => 'post',
                    ],
                ]) ?>
        <?php } ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'dataReserva',
                'value' => date("d-m-Y H:i:s", strtotime($model->dataReserva)),
            ],
            'salaDesc.nome',
            'atividade',
            'tipo',
            [
                'attribute' => 'dataInicio',
                'value' => date("d-m-Y", strtotime($model->dataInicio)),

            ],
            'horaInicio',
            [
                'attribute' => 'dataTermino',
                'value' => date("d-m-Y", strtotime($model->dataTermino)),
            ],
            'horaTermino',
            'solicitante.nome',
        ],
    ]) ?>

</div>
