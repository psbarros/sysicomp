<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\CautelaAvulsa;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CautelaAvulsaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cautelas Avulsas';
$this->params['breadcrumbs'][] = $this->title;

if( Yii::$app->user->identity->checarAcesso('professor') == 1){
  $action = "{view} {update} {delete} {create}";
}
else if ( Yii::$app->user->identity->checarAcesso('secretaria') == 1){
  $action = "{view}";
}

?>
<div class="cautela-avulsa-index">

<?php
		$this->registerJs(
		    "				
			$('#multi-cautela-form').on('submit',function (e) {		
				var ids = $('#cautelaavulsa-idsmulticautela').val().split(',');
				
				//Ao separar uma string vazia a funcao split usada acima cria uma posicao vazia no vetor. entao..
				if(ids[0].trim() == '')
					ids.shift();
				
				var idschecked = $('[name=\"selection[]\"]').filter(':checked');
				var i, ckd;
				
				//Se o usuario clicou em gerar pdf mas nao selecionou nenhuma cautela, nao submetemos..
				if(idschecked.length == 0){
					alert('Para gerar pdf de cautelas, selecione as cautelas desejadas na grade abaixo.');
					return false;
				}
				
				//Aqui checamos se nao ha elemento repetido, pois o evento submit, nao sei porque, eh disparado duas vezes ao clicar no botao Gerar PDF.
				for(i = 0; i<idschecked.length; i++){
					ckd = $(idschecked[i]).val();
					if(ids.indexOf(ckd) < 0)
						ids.push(ckd);
				}
				
				$('#cautelaavulsa-idsmulticautela').val(ids.toString());
				
		        return true;
		    });	
			",
		    View::POS_READY,
		    'check-all-handler'
		);

	?>

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Gerar Cautela Avulsa', ['create'], ['class' => 'btn btn-success']) ?>
        
        <?php $modelCautela = new CautelaAvulsa(); ?>
    	<?php $form = ActiveForm::begin(['action' => ['cautela-avulsa/gerapdfcoletivo'],'options' => ['method' => 'post'], 'id'=>'multi-cautela-form']); ?>
		
		<?= $form->field($modelCautela, 'idsmulticautela')->hiddenInput(['value'=>$modelCautela->idsmulticautela])->label(false);?>
		
        <?php // =Html::beginForm(['controller/produtos'],'post');?>
        <?php //=Html::dropDownList('action','',[''=>'Marque uma opção: ','c'=>'Confirmado','nc'=>'Não Confirmado'],['class'=>'dropdown',])?>
        <?=Html::submitButton('Gerar PDF', ['class' => 'btn btn-info',]);?>
        
        <?php ActiveForm::end(); ?>
        
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn',
			 'checkboxOptions' => function ($model, $key, $index, $column) {
            		return ['value' => $model->idCautelaAvulsa];
           	 },
            ],

            'idCautelaAvulsa',
            //'id',
            'NomeResponsavel',
            'Email:email',
        	[
        		'attribute'=>'ValidadeCautela',
            	'format'=>['date', 'php:d/m/Y'],
        	],
            [
            	'attribute'=>'TelefoneResponsavel',
            	'value'=>"telefoneFormatado",
            ],
            // 'ObservacoesDescarte',
            // 'ImagemCautela',
            [   'label' => 'Status da Cautela',
                'attribute' => 'StatusCautelaAvulsa',
                'filter'=>array ("Em aberto" => "Em aberto", "Concluída" => "Concluída", "Em atraso" => "Em atraso"),
                'value' => 'StatusCautelaAvulsa'
            ],

            ['class' => 'yii\grid\ActionColumn',
            'template'=>'{view} {update} {delete}',
            	'buttons'=>[
        			'delete' => function ($url, $model) {
        			return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'idCautelaAvulsa' => $model->idCautelaAvulsa], [
        					'data' => [
        							'confirm' => 'Remover a cautela avulsa \''.$model->idCautelaAvulsa.'\'?',
        							'method' => 'post',
        					],
        					'title' => Yii::t('yii', 'Remover cautela avulsa'),
        			]);
        			},
        			'view' => function ($url, $model){
        			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'idCautelaAvulsa' => $model->idCautelaAvulsa, 'id' => $model->id], [
        					'title' => Yii::t('yii', 'Visualizar cautela avulsa'),
        			]);
        			},
        			'update' => function ($url, $model){
        			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'idCautelaAvulsa' => $model->idCautelaAvulsa, 'id' => $model->id], [
        					'title' => Yii::t('yii', 'Editar cautela avulsa'),
        			]);
        			},
            	]
        ],
        ],
    ]); ?>
</div>
