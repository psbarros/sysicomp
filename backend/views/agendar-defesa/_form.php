<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use \yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;
use yii\widgets\MaskedInput;
use yii\web\JsExpression;
use app\models\Aluno;
use app\models\AgendarDefesa;
use app\models\MembrosBanca;
use yii\jui\AutoComplete;
use yii\helpers\Url;
use xj\bootbox\BootboxAsset;

BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);
/* @var $this yii\web\View */
/* @var $model app\models\AgendarDefesa */
/* @var $form yii\widgets\ActiveForm */
$tipoDef = ['Q1' => 'Qualificação 1', 'Q2' => 'Qualificação 2', 'Tese' => 'Tese', 'Dissertação' => 'Dissertação'];

$horarios = ["" => "", "07:29" => "07:29", "07:59" => "07:59", "08:29" => "08:29", "08:59" => "08:59", "09:29" => "09:29", "09:59" => "09:59", "10:29" => "10:29", 
            "10:59" => "10:59", "11:29" => "11:29", "11:59" => "11:59", "12:29" => "12:29", "12:59" => "12:59", "13:29" => "13:29", "13:59" => "13:59",
            "14:29" => "14:29", "14:59" => "14:59", "15:29" => "15:29", "15:59" => "15:59", "16:29" => "16:29", "16:59" => "16:59", "17:29" => "17:29", 
            "17:59" => "17:59", "18:29" => "18:29", "18:59" => "18:59", "19:29" => "19:29", "19:59" => "19:59", "20:29" => "20:29", "20:59" => "20:59",
            "21:29" => "21:29", "21:59" => "21:59", "22:29" => "22:29", "22:59" => "22:59"];

$tipoCurso = ['Mestrado' => 'Mestrado', 'Doutorado' => 'Doutorado'];

$tipoConceito = ['Aprovado' => ' Aprovado', 'Reprovado' => 'Reprovado', 'Não Julgado' => 'Não Julgado'];

?>


<div class="agendar-defesa-form">

    <div class="row">
        <div class="col-lg-8">

                <?php $form = ActiveForm::begin(); ?>

                <?php $mAluno  = new Aluno();
                      $mMembro = new Membrosbanca();

                ?>
                <div>
                    <?= $form->field($model, 'numDefesa')->textInput() ?>
                </div>
                <div class="row">
                    <?=
                        $form->field($mAluno, 'nome',['options'=>['class'=>'col-md-1']])->widget(AutoComplete::classname(), [
                            'clientOptions' => [
                                'source' => URL::to(['agendar-defesa/autocompletaluno']),
                                'minLength'=>1,
                                    'select' => new JsExpression("function( event, ui ) {
                                        
                                        $('[name=\"id\"]').val(ui.item.id);                                
                                      }")
                            ],
                            'options'=>[
                                'maxLength'=>100,
                                'style'=>[
                                    'width'=>'680px',
                                ],
                            ]
                        ])->label("<font color='#FF0000'>*</font>Nome Aluno:");

                    ?>
                </div>
                
                <div class="row">

                <?= $form->field($model, 'curso_aluno', ['options' => ['class' => 'col-md-4']])->dropDownList($tipoCurso, ['prompt' => 'Selecione um curso'])->label("<font color='#FF0000'>*</font> <b>Curso:</b>") ?>

                <?= $form->field($model, 'tipoDefesa', ['options' => ['class' => 'col-md-5']])->dropDownList($tipoDef, ['prompt' => 'Selecione um tipo de defesa'])->label("<font color='#FF0000'>*</font> <b>Tipo:</b>") ?>                
               
               </div>

                <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>
                                              

                <div class="row">

                    <?= $form->field($model, 'data', ['options' => ['class' => 'col-md-6']])->widget(DatePicker::classname(), [
                                'language' => Yii::$app->language,
                                'options' => ['placeholder' => 'Selecione a Data de Início ...',],
                                'pluginOptions' => [
                                    'format' => 'dd-mm-yyyy',
                                    'todayHighlight' => true
                                ]
                            ]);
                    ?>


                    <?= $form->field($model, 'horario', ['options' => ['class' => 'col-md-3']])->widget(DateControl::classname(), [
                        'language' => 'pt-BR',
                        'name'=>'kartik-date',
                        'options' => [
                            'pluginOptions' => [
                                'minuteStep' => 30,
                            ],
                        ],
                        'value' => date(''),
                        'type'=>DateControl::FORMAT_TIME,
                        'displayFormat' => 'php: H:i',
                    ])->label("<font color='#FF0000'>*</font> <b>Horário:</b>") ?>

                    

                </div> 

                <div class="row">      

                <?= $form->field($model, 'banca_id', ['options' => ['class' => 'col-md-3']])->textInput() ?>  

                <?= $form->field($model, 'conceito', ['options' => ['class' => 'col-md-5']])->dropDownList($tipoConceito, ['prompt' => 'Selecione um conceito'])?> 

                </div>             

                <?= $form->field($model, 'local')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'resumo')->textarea(['rows' => 6]) ?>   


                <div class="form-group">
                    <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Cancelar', ['defesa/index',], ['class' => 'btn btn-danger']) ?> 
                </div>

                <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

