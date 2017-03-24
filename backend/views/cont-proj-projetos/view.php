<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjProjetos */

$coordenador = \app\models\User::find()->select("*")->where("id=$model->coordenador_id")->one();
$agencia = \backend\models\ContProjAgencias::find()->select("*")->where("id=$model->agencia_id")->one();
$banco =\backend\models\ContProjBancos::find()->select("*")->where("id=$model->banco_id")->one();

$this->title = mb_strimwidth($model->nomeprojeto,0,50,"...");
$this->params['breadcrumbs'][] = ['label' => 'Projetos de Pesquisa e desenvolvimento', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//$coordenadores = ArrayHelper::map(\app\models\User::find()->orderBy('nome')->all(), 'id', 'nome');
//$agencias = ArrayHelper::map(\backend\models\ContProjAgencias::find()->orderBy('nome')->all(), 'id', 'nome');
//$bancos = ArrayHelper::map(\backend\models\ContProjBancos::find()->orderBy('nome')->all(), 'id', 'nome');


?>
<div class="cont-proj-projetos-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
		<?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
			['index'], ['class' => 'btn btn-warning']) ?>
		<?= Html::a('<span class="glyphicon glyphicon-edit"></span> Atualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-remove"></span> Deletar', ['delete', 'id' => $model->id, 'detalhe'=>true], [

            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Você tem certeza que deseja excluir este projeto?',
                'method' => 'post',
            ],
        ]) ?>
		<button class="btn btn-info" data-toggle="collapse" data-target="#prestacao"><span class="glyphicon glyphicon-lock"></span>  Prestação de Contas</button>
    </p>
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<link href="css/docs.min.css" rel="stylesheet">
	<div class=bs-glyphicons> 
		 <?= Html::a('<span class="glyphicon glyphicon-th-list" aria-hidden=true></span> <span class=glyphicon-class>Rubricas do Projeto</span>', ['cont-proj-rubricasde-projetos/index', 'idProjeto' => $model->id], ['class' => 'btn btn-primary']) ?> 
		<?= Html::a('<span class="glyphicon glyphicon-usd" aria-hidden=true></span> <span class=glyphicon-class>Receitas do Projeto</span>', ['cont-proj-receitas/index', 'idProjeto' => $model->id], ['class' => 'btn btn-primary']) ?>		
		<?= Html::a('<span class="glyphicon glyphicon-shopping-cart" aria-hidden=true></span> <span class=glyphicon-class>Despesas do Projeto</span>', ['cont-proj-despesas/index', 'idProjeto' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-time" aria-hidden=true></span> <span class=glyphicon-class>Prorrogação de Projeto</span>', ['cont-proj-prorrogacoes/index', 'idProjeto' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('<span class="glyphicon glyphicon-calendar" aria-hidden=true></span> <span class=glyphicon-class>Eventos do Projeto</span>',['cont-proj-registra-datas/index', 'idProjeto' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('<span class="glyphicon glyphicon-transfer" aria-hidden=true></span> <span class=glyphicon-class>Alteração Orçamentária</span>',['cont-proj-transferencias-saldo-rubricas/index', 'idProjeto' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('<span class="glyphicon glyphicon-file" aria-hidden=true></span> <span class=glyphicon-class>Relatório Simples</span>',
            ['cont-proj-projetos/relatorio', 'idProjeto' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('<span class="glyphicon glyphicon-list-alt" aria-hidden=true></span> <span class=glyphicon-class>Relatório Detalhado</span>',
            ['cont-proj-projetos/detalhado', 'idProjeto' => $model->id], ['class' => 'btn btn-primary']) ?>
	</div> 

    <p>
		
		<div id="prestacao" class="collapse">
			<?= Html::a('<span class="glyphicon glyphicon-print" aria-hidden=true></span> <span class=glyphicon-class>Prestação de Contas FAPEAM 1</span>',
				['cont-proj-projetos/form1', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
			<?= Html::a('<span class="glyphicon glyphicon-print" aria-hidden=true></span> <span class=glyphicon-class>Prestação de Contas FAPEAM 2</span>',
				['cont-proj-projetos/form2', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
			<?= Html::a('<span class="glyphicon glyphicon-print" aria-hidden=true></span> <span class=glyphicon-class>Prestação de Contas FAPEAM 3</span>',
				['cont-proj-projetos/form3', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
			<?= Html::a('<span class="glyphicon glyphicon-print" aria-hidden=true></span> <span class=glyphicon-class>Prestação de Contas FAPEAM 8</span>',
				['cont-proj-projetos/form8', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
			<?= Html::a('<span class="glyphicon glyphicon-download" aria-hidden=true></span> <span class=glyphicon-class>Planilha de Acompanhamento</span>',
				['cont-proj-projetos/excel', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		</div>
		
    </p>
    <!--['label' => 'transferencia de rubricas', 'icon' => 'fa fa-calendar',
    'url' => ['cont-proj-rubricasde-projetos/index'], 'visible' => Yii::$app->user->identity->checarAcesso('professor'),],-->
    <!--['label' => 'rubricas de projeto', 'icon' => '', 'url' => ['cont-proj-rubricasde-projetos/index'],
    'visible' => Yii::$app->user->identity->checarAcesso('professor'),],-->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b>Dados do Projeto</b></h3>
        </div>
        <div class="panel-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'coordenador',
            'nomeprojeto',
            [
                'attribute' => 'coordenador_id',
                'value' => $coordenador->nome,
            ],
            'orcamento:currency',
            'saldo:currency',
            [
                'attribute' => 'data_inicio',
                'value' => date("d/m/Y", strtotime($model->data_inicio)),

            ],
            [
                'attribute' => 'data_fim',
                'value' => date("d/m/Y", strtotime($model->dataMaior())),
            ],
            [
                'attribute' => 'agencias_id',
                'label' => 'Agencia de Fomento',
                'value' => $agencia->nome,
            ],
            [
                'attribute' => 'bancos_id',
                'label' => 'Banco',
                'value' => $banco->nome,
            ],
            'agencia',
            'conta',
            [
                'attribute' => 'edital',
                //'value' => "<a href=localhost/novoppgi/backend/web/".$model->edital."' target = '_blank'> Baixar </a>",
                'format'=>'raw',
                'value' => "<a href='".$model->edital."' target = '_blank'> Baixar </a>"
            ],
            [
                'attribute' => 'proposta',
                //'value' => "<a href=localhost/novoppgi/backend/web/".$model->edital."' target = '_blank'> Baixar </a>",
                'format'=>'raw',
                'value' => "<a href='".$model->proposta."' target = '_blank'> Baixar </a>"
            ],

            'status',
        ],
    ]) ?>

</div>
