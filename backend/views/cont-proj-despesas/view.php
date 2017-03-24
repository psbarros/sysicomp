<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjDespesas */
$idProjeto = Yii::$app->request->get('idProjeto');

$this->title = $model->descricao;
$this->params['breadcrumbs'][] = ['label' => 'Despesas', 'url' => ['index','idProjeto'=>$idProjeto]];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="cont-proj-despesas-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['index', 'idProjeto' => $idProjeto], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Deletar', ['delete', 'id' => $model->id, 'idProjeto'=>$idProjeto, ], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <!--<p>
        <?= Html::a('Atualizar', ['update', 'id' => $model->id, 'idProjeto'=>$idProjeto], ['class' => 'btn btn-primary']) ?>
    </p>-->

    <?= $this->render('..\cont-proj-projetos\dados.php', [
        'idProjeto' => $idProjeto,
    ]) ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b>Dados da despesa</b></h3>
        </div>
        <div class="panel-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'rubricasdeprojetos_id',
                        'label'=> 'Rubrica',
                        'format'=>'ntext',
                        'value' => $rubricasDeProjeto[$model->rubricasdeprojetos_id],
                    ],
                    'descricao',
                    'valor_despesa:currency',
                    'tipo_pessoa',
                    [
                        'attribute' => 'data_emissao',
                        'format' => ['date', 'php:d/m/Y'],
                    ],
                    'ident_nf',
                    'nf',
                    'ident_cheque',
                    [
                        'attribute' => 'data_emissao_cheque',
                        'format' => ['date', 'php:d/m/Y'],
                    ],
                    'valor_cheque:currency',
                    'favorecido',
                    'cnpj_cpf',
                    [
                        'attribute' => 'comprovante',
                        //'value' => "<a href=localhost/novoppgi/backend/web/".$model->edital."' target = '_blank'> Baixar </a>",
                        'format'=>'raw',
                        'value' => $model->comprovante ? "<a href='".$model->comprovante."' target = '_blank'> Baixar </a>" : "Não há comprovante"
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
