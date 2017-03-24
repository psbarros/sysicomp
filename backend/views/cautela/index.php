<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use backend\models\Cautela;
use yii\web\JsExpression;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CautelaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gerenciar Cautelas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cautela-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php
		$this->registerJs(
		    "				
			$('#multi-cautela-form').on('submit',function (e) {		
				var ids = $('#cautela-idsmulticautela').val().split(',');
				
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
				
				$('#cautela-idsmulticautela').val(ids.toString());
				
		        return true;
		    });	
			",
		    View::POS_READY,
		    'check-all-handler'
		);

	?>
    <p>

    	<?= Html::a('Gerar Cautela', ['create'], ['class' => 'btn btn-success']) ?>
		<?php $modelCautela = new Cautela(); ?>
    	<?php $form = ActiveForm::begin(['action' => ['cautela/produtos2'],'options' => ['method' => 'post'], 'id'=>'multi-cautela-form']); ?>
		
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
            		return ['value' => $model->idCautela];
           	 },
            ],

            //'idCautela',
            'NomeResponsavel',
            'OrigemCautela',
            'dataInicial',
            'DataDevolucao',
            // 'Email:email',
            // 'TelefoneResponsavel',
            // 'ImagemCautela',
            [
           	'attribute'=>'Equipamento',
            'value'=>'cautelatemequipamento.NomeEquipamento',
            ],
            // 'StatusCautela',
            [   'label' => 'Status da Cautela',
                'attribute' => 'StatusCautela',
                'filter'=>array ("Em aberto" => "Em aberto", "Concluída" => "Concluída", "Em atraso" => "Em atraso"),
                'value' => 'StatusCautela'
            ],
            // 'idEquipamento',
            [
            	'attribute'=>'nomeProjeto',
            	'value'=>'cautelatemprojeto.nomeprojeto',
            ],
            ['class' => 'yii\grid\ActionColumn',
            'template'=>'{view} {update} {delete}',
            	'buttons'=>[
        			'delete' => function ($url, $model) {
        			return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->idCautela], [
        					'data' => [
        							'confirm' => 'Remover a cautela \''.$model->idCautela.'\'?',
        							'method' => 'post',
        					],
        					'title' => Yii::t('yii', 'Remover cautela'),
        			]);
        			},
        			'view' => function ($url, $model){
        			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model->idCautela], [
        					'title' => Yii::t('yii', 'Visualizar cautela'),
        			]);
        			},
        			'update' => function ($url, $model){
        			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->idCautela], [
        					'title' => Yii::t('yii', 'Editar cautela'),
        			]);
        			},
            	]
            ]
        ],
    ]); ?>
</div>
