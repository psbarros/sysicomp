<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model app\models\Candidato */

$this->title = "Detalhes do Candidato";
$this->params['breadcrumbs'][] = ['label' => 'Editais', 'url' => ['edital/index']];
$this->params['breadcrumbs'][] = ['label' => 'Número: '.Yii::$app->request->get('idEdital'), 
    'url' => ['edital/view','id' => Yii::$app->request->get('idEdital') ]];
$this->params['breadcrumbs'][] = ['label' => 'Candidato com Inscrição Encerrada', 
    'url' => ['candidatos/index','id' => Yii::$app->request->get('idEdital') ]];
$this->params['breadcrumbs'][] = $this->title;


$resultado = array(null => " <div style=\"color:red; font-weight:bold\"> Não Julgado <div>" , 0 => "Reprovado", 1 => "Aprovado");
$tipoPos = array (null => " <div style=\"color:red; font-weight:bold\"> Não Registrado </div>" ,'0' => 'Mestrado Acadêmico', '1' => 'Mestrado Profissional', '2' => 'Doutorado');



?>
<div class="candidato-view">

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['candidatos/index', 'id' => $model->idEdital], ['class' => 'btn btn-warning']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'nome',
                [
                     'attribute' => 'inicio',
                     'format'=>'raw',
                     'value' => date("d/m/Y", strtotime($model->inicio)).' às '.date("H:i:s", strtotime($model->inicio))
                ],
                [
                     'attribute' => 'fim',
                     'format'=>'raw',
                     'value' => $model->fim != null ? date("d/m/Y", strtotime($model->fim)).' às '.date("H:i:s", strtotime($model->fim)) : null
                ],
            'endereco',
            'bairro',
            'cidade',
            'uf',
            'cep',
            'email:email',
            'datanascimento',

                [
                     'attribute' => 'nacionalidade',
                     'format'=>'raw',
                     'value' => $model->nacionalidade == 1 ? 'Brasileira' : 'Estrangeira'
                ],
                [
                    'attribute' => 'pais',
                    'format' => 'raw',
                    'value' => $model->nacionalidade == 1 ? 'Brasil' : $model->pais,
                ],

                [
                    'attribute' => 'passaporte',
                    'format' => 'raw',
                    'visible'=> $model->nacionalidade != 1 ,
                    'value' => $model->nacionalidade == 1 ? "<div style=\"color:red; font-weight:bold\"> Não Registrado</div>" : $model->passaporte,
                ],

            'cpf',
                [
                     'attribute' => 'sexo',
                     'format'=>'raw',
                     'value' => $model->sexo == 'M' ? 'Masculino' : 'Feminino'
                ],
            'telresidencial',
//            'telcomercial',
            [
                'attribute' => 'telcelular',
                'format' => 'raw',
                'value' => $model->telcelular == null ? "<div style=\"color:red; font-weight:bold\"> Não Registrado </div>" : $model->telcelular,
            ],


                [
                     'attribute' => 'cursodesejado',
                     'format'=>'raw',
                     'value' => $model->cursodesejado == 1 ? 'Mestrado' : 'Doutorado'
                ],
                [
                     'attribute' => 'regime',
                     'format'=>'raw',
                     'value' => $model->regime == 1 ? 'Integral' : 'Parcial'
                ],
            'inscricaoposcomp',
            'anoposcomp',
            'notaposcomp',
                [
                     'attribute' => 'solicitabolsa',
                     'format'=>'raw',
                     'value' => $model->solicitabolsa == 1 ? 'Sim' : 'Não'
                ],
                [
                     'attribute' => 'cotas',
                     'format'=>'raw',
                     'value' => $model->cotas == 1 ? 'Sim' : 'Não'
                ],
                [
                     'attribute' => 'deficiencia',
                     'format'=>'raw',
                     'value' => $model->deficiencia == 1 ? 'Sim' : 'Não'
                ],
                [
                     'attribute' => 'idLinhaPesquisa',
                     'label'=> 'Linha de Pesquisa',
                ],
                [
                     'attribute' => 'tituloproposta',
                     'label'=> 'Título da Proposta',
                ],
            'motivos:ntext',
                [
                     'attribute' => 'cartaorientador',
                     'label' => 'Carta do Orientador (PDF)',
                     'format'=>'raw',
                     'value' => "<a href='index.php?r=candidatos/pdf&id=".$model->id."&documento=".$model->cartaorientador."' target = '_blank'> Baixar </a>"
                ],

                [
                     'attribute' => 'proposta',
                     'label' => 'Proposta de Trabalho (PDF)',
                     'format'=>'raw',
                     'value' => "<a href='index.php?r=candidatos/pdf&id=".$model->id."&documento=".$model->proposta."' target = '_blank'> Baixar </a>"
                ],
                [
                     'attribute' => 'curriculum',
                     'label' => 'Curriculum (PDF)',
                     'format'=>'raw',
                     'value' => "<a href='index.php?r=candidatos/pdf&id=".$model->id."&documento=".$model->curriculum."' target = '_blank'> Baixar </a>"
                ],
                [
                     'attribute' => 'comprovantepagamento',
                     'label' => 'Comprovante de Pagamento (PDF)',
                     'format'=>'raw',
                     'value' => "<a href='index.php?r=candidatos/pdf&id=".$model->id."&documento=".$model->comprovantepagamento."' target = '_blank'> Baixar </a>"
                ],

                [
                     'label' => 'Cartas de Recomendação (PDF)',
                     'format'=>'raw',
                     'value' => $model->qtdcartasrespondidas > 0 ? "<a href='index.php?r=candidatos/pdf&id=".$model->id."&documento=Cartas.pdf' target = '_blank'> Baixar </a>" : "Cartas Pendentes de Resposta"

                ],
                [
                     'label' => 'Nº de Cartas de Recomendação (Emitidas)',
                     'format'=>'raw',
                     'value' => $model->qtdcartasemitidas,

                ],
                [
                     'label' => 'Nº de Cartas de Recomendação (Respondidas)',
                     'format'=>'raw',
                     'value' => $model->qtdcartasrespondidas,

                ],
            'cursograd',
            'instituicaograd',
            'egressograd',

            [
            'attribute' => 'cursopos',
            'format' => 'html',
            'value' => $model->cursopos == null ? "<div style=\"color:red; font-weight:bold\"> Não Registrado</div>" : $model->cursopos,
            ],

            [
            'attribute' => 'tipopos',
            'format' => 'raw',
            'value' => $tipoPos[$model->tipopos],
            ],
            [
            'attribute' => 'instituicaopos',
            'format' => 'raw',
            'value' => $model->instituicaopos == null ? "<div style=\"color:red; font-weight:bold\"> Não Registrado</div>" : $model->instituicaopos,
            ],
            [
            'attribute' => 'egressopos',
            'format' => 'raw',
            'value' => $model->egressopos == null ? "<div style=\"color:red; font-weight:bold\"> Não Registrado</div>" : $model->egressopos,
            ], 
            [
            'attribute' =>'resultado',
            'label' => 'Resultado da Avaliação',
            'format' => 'html',
            'value' => '<b>'.$resultado[$model->resultado].'</b>',

            ],
        ],
    ]) ?>

</div>
