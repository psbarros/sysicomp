<?php

use yii\helpers\Html;
use yii\grid\GridView;
use xj\bootbox\BootboxAsset;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\Defesa;
use yii\web\JsExpression;

BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\DefesaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gerar Relatórios de Bancas';
$this->params['breadcrumbs'][] = $this->title;

if( Yii::$app->user->identity->checarAcesso('coordenador') == 1){
	$action = "";
}
else if ( Yii::$app->user->identity->checarAcesso('professor') == 1){
	$action = "";
}
else if( Yii::$app->user->identity->checarAcesso('secretaria') == 1){
	$action = "";

}


?>
<div class="defesa-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<?php $this->registerJs('
				$("#defesa-tiporelat").on("change.yii",function(){
					if(this.value == 0){//Todas as Bancas
						$("#defesa-anopesq").prop("disabled", true);
						$("#defesa-anopesq").val("");
					}else if(this.value == 1){
						$("#defesa-anopesq").prop("disabled", false);
						//$("#defesa-anopesq").val("");
					}
				});
			
				$( document ).ready(function() {
				  $("#defesa-anopesq").prop("disabled", true);
				});
		  '); ?>
    <p>
        <?php 
        $form = ActiveForm::begin();
		$form->action=["defesa/gerarrelatoriobanca"];
//        echo Html::a('<span class="glyphicon glyphicon-ok"></span> Gerar Relatório', ['gerarrelatoriobanca',], ['class' => 'btn btn-warning']);
        
        
        ?>
        <div class="panel panel-default" style="width:80%">
        <div class="panel-heading">
        <!-- <h3 class="panel-title"><b>Disciplina Origem:</b></h3> -->
        </div>
        <div class="panel-body">
        
        <div class="row">
        <?php 
        $mDefesa = new Defesa();
        echo $form->field($mDefesa, 'tipoRelat',['options'=>['class'=>'col-md-3']])->dropDownList([0=>'Todas as Bancas', 1=>'Por Ano'])->label("Relatório por:");
        echo $form->field($mDefesa, 'anoPesq',['options'=>['class'=>'col-md-2']])->widget(MaskedInput::className(), [
                    'mask' => '9999', 'clientOptions'=>['alias'=>'numeric']])->label("Ano:");
        
        ?>
        </div>
        </div>
        </div>
        <?php echo Html::hiddenInput("idProfessor", $idProfessor)?>
        
        <?php echo Html::submitButton('Gerar Relatório', ['class' => 'btn btn-warning']) ?>
        <?php ActiveForm::end();?>
        
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'idDefesa',
            'nome_aluno',
            [   'label' => 'Curso Desejado',
                'attribute' => 'curso_aluno',
				'filter'=>array (1 => "Mestrado",2 => "Doutorado"),
                'value' => function ($model) {
                     return $model->curso_aluno == 1 ? 'Mestrado' : 'Doutorado';
                },
            ],
            [   'label' => 'Tipo de Defesa',
                'attribute' => 'tipoDefesa',
				'filter'=>array ("Q1" => "Qualificação 1", "Q2" => "Qualificação 2", "D" => "Dissertação", "T" => "Tese"),
                'value' => 'tipoDefesa'
            ],
            'data',
            [
            "attribute" => 'conceito',
			'filter'=>array ("Aprovado" => "Aprovado", "Reprovado" => "Reprovado"),
            "value" => function ($model){
                return $model->conceito == null ? "Não Julgado" : $model->conceito;

            },
            ],
             'horario',
             'local',

            ['class' => 'yii\grid\ActionColumn',
              'template'=> $action,
                'buttons'=>[
                
                    'view' => function ($url, $model) {  
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'idDefesa' => $model->idDefesa , 'aluno_id' => $model->aluno_id], [
                                'title' => Yii::t('yii', 'Visualizar Detalhes'),
                        ]);                                
                    },
                    'update' => function ($url, $model){
                        return $model->conceito == null ? Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'idDefesa' => $model->idDefesa , 'aluno_id' => $model->aluno_id], ['title' => Yii::t('yii', 'Editar Defesa'),
                        ]) : "";
                    },
                    'delete' => function ($url, $model){
                        return $model->banca->status_banca == null ? Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'idDefesa' => $model->idDefesa , 'aluno_id' => $model->aluno_id], [ 
                                'data' => [
                                    'confirm' => 'Remover a defesa \''.$model->titulo.'\'?',
                                    'method' => 'post',
                                ], 'title' => Yii::t('yii', 'Remover Defesa'),
                        ]) : "";
                    },
                ]                            
            ],
        ],
    ]); ?>
</div>
