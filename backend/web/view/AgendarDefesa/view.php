<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AgendarDefesa */

$this->title = $model->idDefesa;
$this->params['breadcrumbs'][] = ['label' => 'Agendar Defesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agendar-defesa-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id], [
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
            'idDefesa',
            'titulo',
            'tipoDefesa',
            'data',
            'conceito',
            'horario',
            'local',
            'resumo:ntext',
            'numDefesa',
            'examinador:ntext',
            'emailExaminador:ntext',
            'reservas_id',
            'banca_id',
            'aluno_id',
            'previa',
        ],
    ]) ?>

</div>
