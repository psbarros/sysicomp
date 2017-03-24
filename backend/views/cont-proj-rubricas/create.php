<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ContProjRubricas */

$this->title = 'Cadastrar Rubricas';
$this->params['breadcrumbs'][] = ['label' => 'Rubricas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-rubricas-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
        ['index'], ['class' => 'btn btn-warning']) ?>
    </br></br>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
