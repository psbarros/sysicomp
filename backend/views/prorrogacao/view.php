<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Prorrogacao */

$this->title = 'Visualizar Prorrogação';
$this->params['breadcrumbs'][] = ['label' => 'Gerenciar Prorrogações', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prorrogacao-view">

    <!--h1><?= Html::encode($this->title) ?></h1-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['index'], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-edit"></span> Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<span class="fa fa-trash-o"></span> Excluir', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Você tem certeza que deseja excluir esta prorrogação?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'matricula',
                'value' => $model->aluno->matricula
            ],
                //'idAluno',
            [
                'attribute' => 'idAluno',
                'value' => $model->aluno->nome
            ],
            [
                'attribute' => 'orientador',
                'value' => $model->orientador0->nome
            ],
            [
                'attribute' => 'linhaPesquisa',
                'value' => $model->aluno->linhaPesquisa->nome
            ],
            //'dataSolicitacao',
            [
                'attribute' => 'dataSolicitacao',
                'value' => date('d/m/Y', strtotime($model->dataSolicitacao))
            ],
            //'dataInicio',
            [
                'attribute' => 'dataInicio',
                'value' => date('d/m/Y', strtotime($model->dataInicio))
            ],
            //'qtdDias',
            [
                'attribute' => 'qtdDias',
                'label' => 'Quantidade de Dias'
            ],
            'justificativa:ntext',
            [
            'attribute' => 'documento',
            'format' => 'html',
            'value' => '<span class="fa fa-file-pdf-o"></span>   '.
                        Html::a(
                                 //explode('uploads/prorrogacao/', $model->documento)[1],
                                 ' Download do documento',
                                 $model->documento,
                                [
                                    'target' => '_blank',
                                    'title' => Yii::t('yii', 'Download do Documento'),
                                    'data-pjax'=> "0",
                                ]
                        )
            ], 
            [
            'attribute' => 'status',
            'value' => $model->status == 0 ? 'Encerrado' : 'Ativo'
            ], 
        ],
    ]) ?>

</div>
