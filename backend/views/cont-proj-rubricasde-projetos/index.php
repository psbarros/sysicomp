<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjRubricasdeProjetosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$idProjeto = Yii::$app->request->get('idProjeto');


$this->title = "Rubricas do projeto";
//'Cont Proj Rubricasde Projetos';
$this->params['breadcrumbs'][] = $this->title;
//$this->params['breadcrumbs'][] = ['label' => "$nomeProjeto", 'url' => ['index','idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto]];
//$this->params['breadcrumbs'][] = $this->title;

?>
<div class="cont-proj-rubricasde-projetos-index">

    <!--<h1><?= Html::encode("Rubricas de projeto - ".$this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['cont-proj-projetos/view', 'id' => $idProjeto], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Cadastrar nova rubrica para o projeto', ['create','idProjeto'=>$idProjeto], ['class' => 'btn btn-success']) ?>
    </p>
    <?= $this->render('..\cont-proj-projetos\dados.php', [
        'idProjeto' => $idProjeto,
    ]) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'projeto_id',
            //'rubrica_id',
            'nomerubrica',
            'descricao',
            //'nomeprojeto',
            'valor_total:currency',
            'valor_gasto:currency',
            'valor_disponivel:currency',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) use ($idProjeto) {
                        $url .= '&idProjeto=' . $idProjeto; //This is where I want to append the $lastAddress variable.
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
                        //Html::a('Atualizar', ['update', 'id' => $model->id,'idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto ]);
                    },
                    'update' => function ($url, $model) use ($idProjeto) {
                        $url .= '&idProjeto=' . $idProjeto; //This is where I want to append the $lastAddress variable.
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
                        //Html::a('Atualizar', ['update', 'id' => $model->id,'idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id,'idProjeto'=>$model->projeto_id], [
                            'data' => [
                                'confirm' => 'Deseja realmente remover esta rubrica?',
                                'method' => 'post',
                            ],
                            'title' => Yii::t('yii', 'Remover Aluno'),
                        ]);
                    }
                ],

            ],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
