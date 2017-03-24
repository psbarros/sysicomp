<?php

use yii\helpers\Html;
use yoo\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Aproveitamento */

$this->title = 'Aproveitamento';
//$this->params['breadcrumbs'][] = ['label' => 'Aproveitamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
if(isset($fromAluno) && $fromAluno===true)
	$voltar = Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['aproveitamento/indexbyaluno', 'idAluno'=> $model->idAluno], ['class' => 'btn btn-warning']);
else
	$voltar = Html::a('Voltar', ['aproveitamento/index'], ['class' => 'btn btn-warning']);
	?>
<p> <?= $voltar ?> </p>

<div class="aproveitamento-create">

    <!-- <h1><?= Html::encode($this->title) ?></h1>-->
    <?= $this->render('_form', [
        'model' => $model,
    ])  ?>

</div>
