<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\fileInput;


/* @var $this yii\web\View */
/* @var $model app\models\Equipamento */

if ($model->StatusEquipamento == 1){
	$titulo = "Disponível";
}
else if ($model->StatusEquipamento == 2){
	$titulo = "Em uso";
}
else if ($model->StatusEquipamento == 3){
	$titulo = "Descartado";
}

$this->title = 'Cadastrar Equipamento';
$this->params['breadcrumbs'][] = ['label' => 'Equipamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="equipamento-create">

    

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?php
        $form->field($model, 'file')->fileInput() ;

        if($model->ImagemEquipamento){
            echo '<img src="'.\Yii::$app->request->BaseUrl.'/'.$model->ImagemEquipamento.' " width="90px" > &nbsp;&nbsp;&nbsp; ' ;
            echo Html::a('Delete ImagemEquipamento',['equipamento/deleteImagemEquipamento', 'idEquipamento'=>$model->idEquipamento],['class'=>'btn btn-danger']).'<p>';

        }


    ?>

    <?php ActiveForm::end() ?>


    <?= $this->render('_form', [
        'model' => $model,
        //'model->StatusEquipamento' => $StatusEquipamento ,
    ]) ?>

</div>
