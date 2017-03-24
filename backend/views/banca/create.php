<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Banca */

$this->title = 'Create Banca';
$this->params['breadcrumbs'][] = ['label' => 'Bancas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banca-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
