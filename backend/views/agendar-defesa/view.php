<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Aluno;

/* @var $this yii\web\View */
/* @var $model app\models\AgendarDefesa */

$this->title = $model->titulo;
$this->params['breadcrumbs'][] = ['label' => 'Agendar Defesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agendar-defesa-view">

   

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Sair  ', ['defesa/index',], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Atualizar', ['update', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Deletar', ['delete', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'titulo',
            'tipoDefesa',
            'data',
            'conceito',
            'horario',
            'local',
            'resumo:ntext',
            'examinador:ntext',
            'emailExaminador:ntext',
            'banca_id',
             [ 'attribute' => 'Aluno',
               'value' => $model->getNome(),
            ],
        ],
    ]) ?>

</div>
