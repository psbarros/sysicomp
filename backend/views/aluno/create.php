<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Aluno */



$this->title = 'Novo Aluno';
//$this->params['breadcrumbs'][] = ['label' => 'Editais', 'url' => ['edital/index']];
//$this->params['breadcrumbs'][] = ['label' => 'Número: '.Yii::$app->request->get('idEdital'), 
//    'url' => ['edital/view','id' => Yii::$app->request->get('idEdital') ]];
 //   $this->params['breadcrumbs'][] = ['label' => 'Candidatos com inscrição finalizada', 
 //   'url' => ['candidatos/index','id' => Yii::$app->request->get('idEdital') ]];
$this->params['breadcrumbs'][] = $this->title;
?>

<p> <?= Html::a('Voltar', ['candidatos/index', 'id' => Yii::$app->request->get('idEdital')], ['class' => 'btn btn-warning']) ?> </p>


<div class="aluno-create">

    <?= $this->render('_form', [
        'model' => $model,
        'linhasPesquisas' => $linhasPesquisas,
        'orientadores' => $orientadores,
    ]) ?>

</div>
