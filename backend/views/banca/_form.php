<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Banca */
/* @var $form yii\widgets\ActiveForm */


$tipoFuncao = ['I' => 'Membro Interno', 'E' => 'Membro Externo', 'S' => 'Suplente'];

?>






<div class="banca-form">


         <?php $form = ActiveForm::begin(); ?>


         <div class="panel panel-default">

                <div class="panel-heading">

                    <h3 class="panel-title"><b>Componente Banca</b></h3>
                </div>

                <div class="panel-body">
                    <div class="panel-heading">
                                    <h3 class="panel-title"><b>Presidente da Banca</b></h3>
                    </div>
                          <?= $form->field($model, 'membrosbanca_id_1',['options' => ['class' => 'col-md-6 col-right']])->dropDownList($items, ['prompt' => 'Selecione um membro...'])->label("<font color='#FF0000'>*</font> <b>Nome Membro:</b>") ?>
                </div> 

                <div class="panel-body">
                    <div class="panel-heading">
                                    <h3 class="panel-title"><b>Componente 2</b></h3>
                    </div>
                          <?= $form->field($model, 'membrosbanca_id_2',['options' => ['class' => 'col-md-6 col-right']])->dropDownList($items, ['prompt' => 'Selecione um membro...'])->label("<b>Nome Membro:</b>") ?>

                        <?= $form->field($model, 'funcao2',['options' => ['class' => 'col-md-6 col-right']])->dropDownList($tipoFuncao, ['prompt' => 'Selecione uma Função...'])->label("<b>Funcao:</b>") ?>

                </div> 

                <div class="panel-body">
                    <div class="panel-heading">
                                    <h3 class="panel-title"><b>Componente 3</b></h3>
                    </div>
                          <?= $form->field($model, 'membrosbanca_id_3',['options' => ['class' => 'col-md-6 col-right']])->dropDownList($items, ['prompt' => 'Selecione um membro...'])->label("<b>Nome Membro:</b>") ?>

                        <?= $form->field($model, 'funcao3',['options' => ['class' => 'col-md-6 col-right']])->dropDownList($tipoFuncao, ['prompt' => 'Selecione uma Função...'])->label("<b>Funcao:</b>") ?>
                </div> 

                <div class="panel-body">
                    <div class="panel-heading">
                                    <h3 class="panel-title"><b>Componente 4</b></h3>
                    </div>
                          <?= $form->field($model, 'membrosbanca_id_4',['options' => ['class' => 'col-md-6 col-right']])->dropDownList($items, ['prompt' => 'Selecione um membro...'])->label("<b>Nome Membro:</b>") ?>

                          <?= $form->field($model, 'funcao4',['options' => ['class' => 'col-md-6 col-right']])->dropDownList($tipoFuncao, ['prompt' => 'Selecione uma Função...'])->label("<b>Funcao:</b>") ?>
                </div> 

                <div class="panel-body">
                    <div class="panel-heading">
                                    <h3 class="panel-title"><b>Componente 5</b></h3>
                    </div>
                          <?= $form->field($model, 'membrosbanca_id_5',['options' => ['class' => 'col-md-6 col-right']])->dropDownList($items, ['prompt' => 'Selecione um membro...'])->label("<b>Nome Membro:</b>") ?>

                        <?= $form->field($model, 'funcao5',['options' => ['class' => 'col-md-6 col-right']])->dropDownList($tipoFuncao, ['prompt' => 'Selecione uma Função...'])->label("<b>Funcao:</b>") ?>
                </div> 

    </div>
     <div class="form-group">
             <?= Html::submitButton('Salvar', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
     </div>
    <?php ActiveForm::end(); ?>

</div>
