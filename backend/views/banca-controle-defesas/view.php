<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\BancaControleDefesas */

$this->title = "Detalhes da Banca";
$this->params['breadcrumbs'][] = ['label' => 'Banca Controle Defesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$arrayStatusBanca = array(null => "Não Avaliada", 0 => "Reprovada", 1 => "Aprovada");

?>


<div class="banca-controle-defesas-view">

    <p>
		<?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['index'], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('<span class="fa fa-check-circle"></span> Deferir Banca', ["aprovar", 'id' => $model->id], [
            'class' => 'btn btn-primary',
            'data' => [
                'confirm' => 'Você tem certeza que deseja APROVAR essa banca?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('<span class="fa fa-remove"></span> Indeferir Banca', ['update', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Você tem certeza que deseja REPROVAR essa banca?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'aluno_nome',
            'linhaSigla',
            'cursoAluno',
            'titulo',
            'local',
            'horario',
            'data',
            [
            'attribute' => 'status_banca',
            'value' => $arrayStatusBanca[$model->status_banca],
            ],
        ],
    ]) ?>

<h3> Detalhes da Banca </h3>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        "summary" => "",
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'banca_id',
            //'membrosbanca_id',
            [
                'attribute'=>'membro_nome',
                'label' => "Nome do Membro",
            ],
            [
                'attribute'=>'membro_filiacao',
                'label' => "Filiação do Membro",
            ],
            [
                "attribute" => 'funcaomembro',
                "label" => "Função",
            ],

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
