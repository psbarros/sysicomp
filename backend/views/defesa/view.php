	<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use xj\bootbox\BootboxAsset;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;

BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);

$this->title = "Detalhes da Defesa";
$this->params['breadcrumbs'][] = ['label' => 'Defesas', 'url' => ['index', 'idDefesa' => $model->idDefesa]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="defesa-view">

    <p>
    <div class="row" style="margin-left: 10px;">

        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['defesa/index',], ['class' => 'btn btn-warning']) ?>

		<?= $model->conceito == null ? Html::a('<span class="glyphicon glyphicon-edit"></span> Editar', ['update', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id], ['class' => 'btn btn-primary']) : "" ?>

        <?= $model->banca->status_banca == null ? Html::a('<span class="glyphicon glyphicon-remove"></span> Excluir', ['delete', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Deseja remover defesa \''.$model->titulo.'\'?',
                'method' => 'post',
            ],
        ]) : "" ?>

        <?= Html::a('<span class="fa fa-balance-scale"></span> Gerar Portaria', ['gerar_portaria', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id], ['class' => 'btn btn-success']) ?>

        <?php if(Yii::$app->user->identity->secretaria && $model->banca->status_banca == 1){
                Modal::begin([
                  'header' => '<h2>Lançar Conceito</h2>',
                  'toggleButton' => ['label' => '<span class="fa fa-hand-stop-o"></span> Lançar Conceito', 'class' => 'btn btn-success'],
                  'id' => 'modal',
                  'size' => 'modal-md',
                ]);

                $form = ActiveForm::begin();
                echo $form->field($model, 'conceito')->dropDownlist(['Aprovado' => 'Aprovado', 'Reprovado' => 'Reprovado', 'Suspenso' => 'Suspenso'],
                    ['prompt' => 'Selecione um Conceito']);

                echo "<div class='form-group'>";
                echo Html::submitButton($model->isNewRecord ? 'Criar' : 'Alterar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
                echo "</div>";

                ActiveForm::end();

                Modal::end();

				echo Html::a('<span class="glyphicon glyphicon-print"></span>  Convite', ['convitepdf', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id], ['class' => 'btn btn-success', 'target' => '_blank']);
        }

        if($model->banca->status_banca == 1){
			if($model->tipoDefesa == "D" || $model->tipoDefesa == "T"){
				echo Html::a('<span class="glyphicon glyphicon-print"></span> Ata Defesa  ', ['atadefesapdf', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id], ['class' => 'btn btn-success', 'target' => '_blank']);
		        echo Html::a('<span class="glyphicon glyphicon-print"></span> Folha de Aprovação', ['folhapdf', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id], ['class' => 'btn btn-success', 'target' => '_blank']);

			}
			else{
				echo Html::a('<span class="glyphicon glyphicon-print"></span> Folha Qualificação', ['atapdf', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id], ['class' => 'btn btn-success', 'target' => '_blank']);
			}
        }

        ?>
        <?= Yii::$app->user->identity->secretaria ? Html::a('<span class="glyphicon glyphicon-envelope"></span>  Enviar Lembrete de Pendência', ['lembretependencia', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id], ['class' => 'btn btn-primary']) : "" ?>

        </div>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nome',
            [
                'label' => 'E-mail',
                'value' => $model->modelAluno->email,
            ],
            'curso',
            'titulo',
            [
            'attribute' => 'numDefesa',
            'label' => 'Nº da Defesa',
            ]
            ,
            [
            "attribute" => 'tipodefesa',
            "label" => "Tipo",
            ],
			[
            'attribute' => 'examinador',
            'label' => 'Examinador',
			'visible' => ($model->tipoDefesa == "Q1")  ? true : false,
            ]
            ,
            [
            "attribute" => 'data',
            "value" => date("d/m/Y", strtotime($model->data))
            ],
            [
            "attribute" => 'conceitodefesa',
            'format' => 'html',
            "label" => "Conceito",
            ],
            [
            "attribute" => 'previa',
            'format' => 'raw',
              'value' => "<a href='previa/".$model->previa."' target = '_blank'> Baixar </a>"
            ],

            [
            'attribute' => 'horario',
            'visible' => ($model->curso == "Doutorado" && $model->tipoDefesa == "Q1") ? false : true,
            ]
            ,

            [
            'attribute' => 'local',
            'visible' => ($model->curso == "Doutorado" && $model->tipoDefesa == "Q1") ? false : true,
            ]
            ,
            'resumo:ntext',
        ],
    ]) ?>

<?php

	if($model->tipoDefesa != "Q1"){

?>

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
                'attribute'=>'nome',
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

            ['class' => 'yii\grid\ActionColumn',
              'template'=>'{carta} {folha}',
                'buttons'=>[
                  'carta' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-envelope"></span>', ['agradecimentopdf', 'idDefesa' => $_GET['idDefesa'], 'aluno_id' => $_GET['aluno_id'], 'membrosbanca_id' => $model->membrosbanca_id ], [
                            'data' => [
                                'method' => 'post',
                            ],
                            'title' => Yii::t('yii', 'Agradecimento'),
                            'target'=>'_blank',
                    ]);
                  },
                  'folha' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-print"></span>', ['declaracaopdf', 'idDefesa' => $_GET['idDefesa'], 'aluno_id' => $_GET['aluno_id'], 'membrosbanca_id' => $model->membrosbanca_id  ], [
                            'data' => [
                                'method' => 'post',
                            ],
                            'title' => Yii::t('yii', 'Declaração'),
                            'target'=>'_blank',
                    ]);
                  }
              ]
            ],
        ],
    ]); ?>

<?php
	}
?>

</div>
