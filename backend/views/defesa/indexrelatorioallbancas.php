<?php

use yii\helpers\Html;
use yii\grid\GridView;
use xj\bootbox\BootboxAsset;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\Defesa;
use yii\web\JsExpression;
use app\models\MembrosBanca;
use yii\jui\AutoComplete;
use yii\helpers\Url;

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
        /*TALVEZ TENHA QUE MUDAR*/
		$form->action=["defesa/gerarrelatoriobanca"];
//        echo Html::a('<span class="glyphicon glyphicon-ok"></span> Gerar Relatório', ['gerarrelatoriobanca',], ['class' => 'btn btn-warning']);
        
        
        ?>
        <div class="panel panel-default" style="width:90%">
        <div class="panel-heading">
        <!-- <h3 class="panel-title"><b>Disciplina Origem:</b></h3> -->
        </div>
        <div class="panel-body">
        
        <div class="row">
        <?php 
        $mDefesa = new Defesa();
        $mMembro = new Membrosbanca();
        echo $form->field($mDefesa, 'tipoRelat',['options'=>['class'=>'col-md-3']])->dropDownList([0=>'Todas as Bancas', 1=>'Por Ano'])->label("Relatório por:");
        echo $form->field($mDefesa, 'anoPesq',['options'=>['class'=>'col-md-2']])->widget(MaskedInput::className(), [
                    'mask' => '9999', 'clientOptions'=>['alias'=>'numeric']])->label("Ano:");
        ?>
        </div>
        <div class="row">
        <?php 
        echo $form->field($mMembro, 'nome',['options'=>['class'=>'col-md-3']])->widget(AutoComplete::classname(), [
        		'clientOptions' => [
        				'source' => URL::to(['defesa/autocompletemembro']),
        				'minLength'=>3,
        				'select' => new JsExpression("function( event, ui ) {
								        //console.log(ui);
								        //$('disciplina-1-nome').val(ui.item.nome);
								        $('[name=\"idProfessor\"]').val(ui.item.id);        						
								      }")
        		],
        		'options'=>[
        				'maxLength'=>100,
        				'style'=>[
        						'width'=>'346px',
        				],
        		]
        ])->label("<font color='#FF0000'>*</font> <b>Nome Membro:</b>"); ?>
        
        </div>
        </div>
        </div>
        <?php echo Html::hiddenInput("idProfessor", $idProfessor)?>
        <?php echo Html::hiddenInput("listall", "listall")?>
        
        <?php echo Html::submitButton('Gerar Relatório', ['class' => 'btn btn-warning']) ?>
        <?php ActiveForm::end();?>
    </p>
    
</div>
