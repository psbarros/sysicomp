<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Aluno */

$this->title = "Dados do Aluno: ".$model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Alunos', 'url' => ['index']];
$this->params['breadcrumbs'][] = "Aluno: ".$model->nome;

$statusAluno = array(0 => 'Aluno Corrente',1 => 'Aluno Egresso',2 => 'Aluno Desistente',3 => 'Aluno Desligado',4 => 'Aluno Jubilado',5 => 'Aluno com Matrícula Trancada');

?>
<div class="aluno-view">
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['orientandos'], ['class' => 'btn btn-warning']) ?>
		<?= Html::a('Pedir Banca', ['defesa/create', 'aluno_id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

        <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b>Dados Pessoais</b></h3>
        </div>
        <div class="panel-body">
        <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nome',
            'email:email',
            [
                     'attribute' => 'curso',
                     'label'=> 'Endereço',
                     'value' => $model->endereco. ", Bairro ".$model->bairro.", Cidade ".$model->cidade. "-".$model->uf. ", CEP ".$model->cep
            ],
            'cpf',
            'telresidencial',
            'telcelular',
            'cursograd',
            'instituicaograd',
            'egressograd',
        ],
        ]) ?>           
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b>Dados do Aluno</b></h3>
        </div>
        <div class="panel-body">
        <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           'matricula',
            'orientador',
            [
                     'attribute' => 'area',
                     'label'=> 'Linha de Pesquisa',
                ],
            [
                     'attribute' => 'curso',
                     'format'=>'raw',
                     'value' => $model->curso == 1 ? 'Mestrado' : 'Doutorado'
                ],
            [
                     'attribute' => 'bolsista',
                     'format'=>'raw',
                     'value' => $model->bolsista == 1 ? 'SIM: '.$model->financiadorbolsa.' implementada em '.date("d/m/Y", strtotime($model->dataimplementacaobolsa)) : 'NÃO'
            ],
            [   'label' => 'Status',
                'attribute' => 'status',
                'value' => $model->status == 0 ? 'Aluno Corrente': 'Aluno Egresso' 
            ],
            [
                'label' => 'Data de Ingresso',
                'attribute' => 'dataingresso',
                'value' => date("d/m/Y", strtotime($model->dataingresso)),

            ],
        ],
        ]) ?>           
        </div>
    </div>
        <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b>Exame de Proficiência</b></h3>
        </div>
        <div class="panel-body">
        <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idiomaExameProf',
            'conceitoExameProf',
            [
                'label' => 'Data do Exame de Proficiência',
                'attribute' => 'dataExameProf',
                'value' => $model->dataExameProf ? date("d/m/Y", strtotime($model->dataExameProf)): "",

            ],
        ], 
        ]) ?>           
        </div>
    </div>
</div>
