<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Banca */

$this->title = 'Criar Banca';
$this->params['breadcrumbs'][] = ['label' => 'Bancas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banca-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'model1' => $model1,
        'model2' => $model2,
        'model3' => $model3,
        'model4' => $model4,
        'model_membro' => $model_membro,
        'model_defesa' => $model_defesa,
        'items' => $items,
        'items_defesa' => $items_defesa,
    ]) ?>

</div>
