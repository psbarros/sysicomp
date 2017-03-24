<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Cautela;
/* @var $this yii\web\View */
/* @var $model backend\models\Cautela */

$this->title = $model->NomeResponsavel;
$this->params['breadcrumbs'][] = ['label' => 'Cautelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cautela-view">



     <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['cautela/index'], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Atualizar', ['update', 'id' => $model->idCautela], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Deletar', ['delete', 'id' => $model->idCautela], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>

       <?= Html::a('<span class="glyphicon glyphicon-file"></span> Gerar Pdf', ['cautela/produtos', 'id' => $model->idCautela], [
                            'target' => '_blank', 'class' => 'btn btn-info']) ?>

		<?php if($model->StatusCautela !== Cautela::getStatusConcluida()){ ?>
        <?php echo Html::a('Dar Baixa Cautela', ['baixa-cautela/create', 'idCautela' => $model->idCautela], [
            'class' => 'btn btn-success',
            'data' => [
                'confirm' => 'Você tem certeza que deseja dar baixa nesta Cautela?',
                'method' => 'post',
                //$model->flagCautela = 1,
               // $this->redirect(array('descarte-equipamento/create', 'id' => $model->idEquipamento)),
            ],
        ]); 
	       //Cautela Concluida pode ter sua Baixa Revertida..
       	}elseif($model->baixaReversivel){
       		echo Html::a('Reverter Baixa', ['baixa-cautela/revert', 'idCautela' => $model->idCautela], [
       				'class' => 'btn btn-success',
       				'data' => [
       						'confirm' => 'Você tem certeza que deseja Reverter esta Baixa?',
       						'method' => 'post',
       						//$model->flagCautela = 1,
       						// $this->redirect(array('descarte-equipamento/create', 'id' => $model->idEquipamento)),
       				],
       		]);
        }?>
    </p>
<div class="panel panel-default" style="width:60%">
<div class="panel-heading">
                <h3 class="panel-title"><b>Dados Cautela:</b></h3>
</div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idCautela',
            'NomeResponsavel',
            'OrigemCautela',
        	'dataInicial',
            'DataDevolucao',
        	[
        		'attribute'=>'validade',
        		'value'=>$model->Validade,
    		],
        	'Email:email',
            //'ValidadeCautela',
            'TelefoneResponsavel',
            'StatusCautela',
            [
            	'attribute'=>'idProjeto',
            	'value'=>$model->cautelatemprojeto->nomeprojeto,
            ],
        	[
	        	'attribute' => 'ImagemCautela',
	        	//'value' => "<a href=localhost/novoppgi/backend/web/".$model->edital."' target = '_blank'> Baixar </a>",
	        	'format'=>['image', ['width'=>100, 'height'=>100]],
	        	//'value' => "<a href='".$model->ImagemCautela."' target = '_blank'> Foto  </a>"
        		'visible'=>((trim($model->ImagemCautela)!='')?true:false)
        	],
        ],
    ]) ?>
</div>    
   
<div class="panel panel-default" style="width:60%">
<div class="panel-heading">
                <h3 class="panel-title"><b>Dados Equipamento:</b></h3>
</div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        	'idEquipamento',
            [
            	'attribute'=>'NomeEquipamento',
            ],
        	'cautelatemequipamento.StatusEquipamento',
        	[
        		'label'=>$model->cautelatemequipamento->getAttributeLabel('ImagemEquipamento'),
        		'attribute' => 'cautelatemequipamento.ImagemEquipamento',
        		//'value' => "<a href=localhost/novoppgi/backend/web/".$model->edital."' target = '_blank'> Baixar </a>",
        		'format'=>['image', ['width'=>100, 'height'=>100]],
        		//'value' => "<a href='".$model->ImagemEquipamento."' target = '_blank'> Foto  </a>"
        		'visible'=>((trim($model->cautelatemequipamento->ImagemEquipamento)!='')?true:false)
        	],
        ],
    ]) ?>
</div>
<?php if($model->cautelaTemBaixa !== false){
	echo '
<div class="panel panel-default" style="width:60%">
<div class="panel-heading">
                <h3 class="panel-title"><b>Dados Baixa:</b></h3>
</div>';

 echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'cautelaTemBaixa.Recebedor',
            'cautelaTemBaixa.DataDevolucao',
            'cautelaTemBaixa.ObservacaoBaixaCautela',
        ],
    ]);
echo "</div>";} ?>
</div>
