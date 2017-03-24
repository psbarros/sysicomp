<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjRubricasdeProjetosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Rubricas do projeto";
//'Cont Proj Rubricasde Projetos';
$coordenador = \yii\helpers\ArrayHelper::map(\app\models\User::find()->orderBy('nome')->all(),
    'id', mb_strimwidth('nome', 0, 50, "..."));
$this->params['breadcrumbs'][] = $this->title;
//$this->params['breadcrumbs'][] = ['label' => "$nomeProjeto", 'url' => ['index','idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto]];
//$this->params['breadcrumbs'][] = $this->title;

?>
<div class="cont-proj-rubricasde-projetos-index">


    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b>Dados da Rubrica</b></h3>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'rubrica_id')->dropDownList($rubricas, ['prompt' => 'Selecione uma rubrica']); ?>

            <?= $form->field($model, 'valor_disponivel')->widget(\kartik\money\MaskMoney::classname(), [
                'pluginOptions' => [
                    'value' => 0.00,
                    'prefix' => 'R$ ',
                    'suffix' => '',
                    'allowNegative' => false
                ]
            ]); ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'buscar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <!--<h1><?= Html::encode("Rubricas de projeto - " . $this->title) ?></h1>-->


</div>
