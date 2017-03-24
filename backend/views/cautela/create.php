<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Cautela */

$this->title = 'Cadastrar Cautela';
$this->params['breadcrumbs'][] = ['label' => 'Cautelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cautela-create">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
