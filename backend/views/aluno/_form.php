<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\SwitchInput;
use kartik\widgets\DatePicker;
use yii\widgets\MaskedInput;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Aluno */
/* @var $form yii\widgets\ActiveForm */
$divRow = "<div class='row' style=\"margin-bottom:10px;\">";
$divFechar = "</div>";

$ufs = ["AC" => "AC", "AL" => "AL", "AM" => "AM", "AP" => "AP", "BA" => "BA", "CE" => "CE", "DF" => "DF",
"ES" => "ES", "GO" => "GO", "MA" => "MA", "MG" => "MG", "MS" => "MS", "MT" => "MT", "PA" => "PA",
"PB" => "PB", "PE" => "PE", "PI" => "PI", "PR" => "PR", "RJ" => "RJ", "RN" => "RN", "RO" => "RO",
"RR" => "RR", "RS" => "RS", "SC" => "SC", "SE" => "SE", "SP" => "SP", "TO" => "TO"];

$statusAluno = [0 => 'Aluno Corrente',1 => 'Aluno Egresso',2 => 'Aluno Desistente',3 => 'Aluno Desligado',4 => 'Aluno Jubilado',5 => 'Aluno com Matrícula Trancada'];
$financiadoresbolsa = ['CAPES' => 'CAPES', 'FAPEAM' => 'FAPEAM', 'CNPQ' => 'CNPQ'];
$sedes = ['RR' => 'Boa Vista/RR', 'AM' => 'Manaus/AM', 'AC' => 'Rio Branco/AC'];

?>

<div class="aluno-form">

    <?php $form = ActiveForm::begin(); ?>
    
        <input type="hidden" id = "form_bolsista" value = '<?= $model->bolsista?>'/>
        <div class="panel panel-default" style="width:80%">
            <div class="panel-heading">
                <h3 class="panel-title"><b>Dados Pessoais</b></h3>
            </div>
            <div class="panel-body">
			<div class="row">
                <?= $form->field($model, 'nome' , ['options' => ['class' => 'col-md-4']] )->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Nome Completo:</b>"); ?>
                <?= $form->field($model, 'email' , ['options' => ['class' => 'col-md-3']] )->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Email:</b>"); ?>
				</div>
				<div class="row">
                <?= $form->field($model, 'endereco' , ['options' => ['class' => 'col-md-4']] )->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Endereço:</b>");         ?>
                <?= $form->field($model, 'bairro' , ['options' => ['class' => 'col-md-3']] )->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Bairro:</b>");    ?>
                </div>
				<div class="row">
                <?= $form->field($model, 'cidade' , ['options' => ['class' => 'col-md-4']] )->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Cidade:</b>");        ?>
                <?= $form->field($model, 'uf', ['options' => ['class' => 'col-md-3']])->dropDownList($ufs, ['prompt' => 'Selecione UF..'])->label("<font color='#FF0000'>*</font> <b>Estado:</b>"); ?>
                </div>
				<div class="row">
                <?= $form->field($model, 'cep', ['options' => ['class' => 'col-md-4']])->widget(MaskedInput::className(), [
    'mask' => '99999-999'])->label("<font color='#FF0000'>*</font> <b>CEP:</b>"); ?>
                <?= $form->field($model, 'cpf', ['options' => ['class' => 'col-md-3']])->widget(MaskedInput::className(), [
                    'mask' => '999.999.999-99'])->label("<font color='#FF0000'>*</font> <div style='display:inline;' id = 'corCPF'><b>CPF:</b> </div>") ?> 
				</div>
				<div class="row">
                <?= 
                $form->field($model, 'datanascimento', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
                    'language' => Yii::$app->language,
                    'options' => ['placeholder' => 'Selecione a Data de Nascimento...',],
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true
                    ]
                ])->label("<font color='#FF0000'>*</font> <b>Data de Nascimento:</b>"); ?>
				<?= $form->field($model, 'sexo', ['options' => ['class' => 'col-md-3']])->radioList(['M' => 'Masculino', 'F' => 'Feminino'])->label("<font color='#FF0000'>*</font> <b>Sexo:</b>"); ?>
				</div>
				<div class="row">
                <?= $form->field($model, 'telresidencial', ['options' => ['class' => 'col-md-4']])->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => '(99) 99999-9999'])->label("<font color='#FF0000'>*</font> <b>Telefone Principal:</b>"); ?>
                <?= $form->field($model, 'telcelular', ['options' => ['class' => 'col-md-3']])->widget(MaskedInput::className(), [
                'mask' => '(99) 99999-9999'])->label("Telefone Alternativo:");?>
				</div>
            </div>
        </div>
				
		<div class="panel panel-default" style="width:80%">
            <div class="panel-heading">
                <h3 class="panel-title"><b>Dados da Graduação</b></h3>
            </div>
            <div class="panel-body">				
				<div class="row">
                <?= $form->field($model, 'cursograd' , ['options' => ['class' => 'col-md-7']])->textInput(['maxlength' => true])->label(" <div style='display:inline;' id = 'corCPF'><b>Curso:</b> </div>"); ?>
				</div>
				<div class="row">
                <?= $form->field($model, 'instituicaograd' , ['options' => ['class' => 'col-md-4']] )->textInput(['maxlength' => true])->label(" <div style='display:inline;' id = 'corCPF'><b>Instituição onde cursou:</b> </div>");?>
                <?= $form->field($model, 'egressograd' , ['options' => ['class' => 'col-md-3']] )->textInput()->label(" <div style='display:inline;' id = 'corCPF'><b>Ano de Formatura:</b> </div>"); ?>
				</div>
            </div>
        </div>
        <div class="panel panel-default" style="width:80%">
            <div class="panel-heading">
                <h3 class="panel-title"><b>Dados do Aluno</b></h3>
            </div>
            <div class="panel-body">
                <div class="row">
				<?= $form->field($model, 'matricula' , ['options' => ['class' => 'col-md-4']] )->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Matricula:</b>");?>
                <?= $form->field($model, 'sede', ['options' => ['class' => 'col-md-3']])->dropDownList($sedes, ['prompt' => 'Sede..'])->label("<font color='#FF0000'>*</font> <b>Sede:</b>"); ?>
				</div>
				<div class="row">
                <?= $form->field($model, 'orientador', ['options' => ['class' => 'col-md-4']])->widget(Select2::classname(), [
                    'data' => $orientadores,
                    'options' => ['placeholder' => 'Selecione um orientador ...'],
                    'pluginOptions' => [
                    'allowClear' => true
                    ],
                ])->label("<font color='#FF0000'>*</font> <b>Orientador:</b>"); ?>              
                <?= $form->field($model, 'curso', ['options' => ['class' => 'col-md-3']])->radioList(['1' => 'Mestrado', '2' => 'Doutorado'])->label("<font color='#FF0000'>*</font> <b>Curso:</b>");?>
				</div>
				<div class="row">
			    <?= $form->field($model, 'area' , ['options' => ['class' => 'col-md-4']] )->dropDownlist($linhasPesquisas, ['prompt' => 'Selecione uma Linha de Pesquisa'])->label("<font color='#FF0000'>*</font> <b>Linha de Pesquisa:</b>");?>
                <?= $form->field($model, 'regime', ['options' => ['class' => 'col-md-3']])->radioList(['1' => 'Integral', '2' => 'Parcial'])->label("<font color='#FF0000'>*</font> <b>Regime de Dedicação:</b>");?>
				</div>
				<div class="row">
                <?= $form->field($model, 'dataingresso', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
                    'language' => Yii::$app->language,
                    'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true]
                ])->label("<font color='#FF0000'>*</font> Data de Ingresso:");?>
                <?= $form->field($model, 'status', ['options' => ['class' => 'col-md-3']])->dropDownList($statusAluno, ['prompt' => 'Selecione o status..'])->label("<font color='#FF0000'>*</font> <b>Status Corrente:</b>"); ?>
				</div>
				<div class="row">
                <?= $form->field($model, 'bolsista' , ['options' => ['class' => 'col-md-5']] )->widget(SwitchInput::classname(), [
					'pluginOptions' => [
						'size' => 'large',
                        'onText' => 'Sim',
                        'offText' => 'Não',
                ]])->label("Bolsista?"); ?>
				</div>
				<div class="row">
                <div id='divAgencia' style='display: none;width:100%'>
                    <?= $form->field($model, 'financiadorbolsa' , ['options' => ['class' => 'col-md-4']]  )->dropDownlist($financiadoresbolsa, ['prompt' => 'Selecione um Financiador'])->label("<font color='#FF0000'>*</font> Financiador da Bolsa: "); ?>
                    <?= $form->field($model, 'dataimplementacaobolsa', ['options' => ['class' => 'col-md-3']])->widget(DatePicker::classname(), [
                        'language' => Yii::$app->language,
                        'pluginOptions' => [
							'format' => 'dd-mm-yyyy',
							'todayHighlight' => true]
                    ])->label("<font color='#FF0000'>*</font> Início da Vigência:");?>
                </div>
				</div>
            </div>
        </div>
        <div class="panel panel-default" style="width:80%">
            <div class="panel-heading">
                <h3 class="panel-title"><b>Exame de Proficiência</b></h3>
            </div>
            <div class="panel-body">
                <div class="row">
				<?= $form->field($model, 'idiomaExameProf' , ['options' => ['class' => 'col-md-6']] )->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Idioma:</b>");?>
				</div>
				<div class="row">
				<?= $form->field($model, 'conceitoExameProf', ['options' => ['class' => 'col-md-3']])->dropDownlist(['Aprovado' => 'Aprovado', 'Reprovado' => 'Reprovado'], ['prompt' => 'Selecione um Conceito...'])->label("<font color='#FF0000'>*</font> <b>Conceito Obtido:</b>");
            ?>
                <?= $form->field($model, 'dataExameProf', ['options' => ['class' => 'col-md-3']])->widget(DatePicker::classname(), [
                    'language' => Yii::$app->language,
                    'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true]
                ])->label("<font color='#FF0000'>*</font> Data do Exame:");?>
				</div>
            </div>
        </div>
    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-primary']); ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>