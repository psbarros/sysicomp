<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \kartik\money\MaskMoney;
use \kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjReceitasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$idProjeto = Yii::$app->request->get('idProjeto');

$this->title = "Receitas do projeto";
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="cont-proj-receitas-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['cont-proj-projetos/view', 'id' => $idProjeto], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Cadastrar nova receita', ['create','idProjeto'=>$idProjeto], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $this->render('..\cont-proj-projetos\dados.php', [
        'idProjeto' => $idProjeto,
    ]) ?>
	
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'codigo',
            'nomeRubrica',
            [
                'attribute'=>'descricao',
                'format'=>'text',
                'contentOptions'=>['style'=>'max-width: 40%;'],
            ],
            //'valor_receita:currency',
            [
                'attribute'=>'valor_receita',
                'format'=>'currency',
                /*'filter'=> MaskMoney::widget([
                    'model' => $searchModel,
                    'name' => 'valor_receita',
                    //'value' => 0.00,
                    'pluginOptions' => [
                        'prefix' => 'R$ ',
                    ],
                    ]),*/
            ],
            [
                'attribute'=>'data',
                'format' => ['date', 'php:d/m/Y'],
                /*'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'name' => 'data',
                    'options' => ['placeholder' => 'Data ...'],
                    'pluginOptions' => [
                        'format' => 'dd-M-yyyy',
                        'todayHighlight' => true
                    ]
                ]),*/
            ],
            'ordem_bancaria',
            //'tipo',
            [
                'attribute' => 'tipo',
                'filter' => Html::activeDropDownList($searchModel, 'tipo', \yii\helpers\ArrayHelper::map(\backend\models\ContProjRubricas::find()->distinct()->all(), 'tipo', 'tipo'),[  'class'=>'form-control','prompt' => 'Todos os tipos ']),

                'format' => 'html',
                'value' => function ($model){
                    return $model->tipo;
                    //return "<span class='fa ". $model->tipo ." fa-lg'/> ". $model->siglaLinhaPesquisa;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) use ($idProjeto) {
                        $url .= '&idProjeto=' . $idProjeto; //This is where I want to append the $lastAddress variable.
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
                        //Html::a('Atualizar', ['update', 'id' => $model->id,'idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto ]);
                    },
                    'update' => function ($url, $model) {
                        return false;
                    },
                    'delete' => function ($url, $model) use ($idProjeto) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id,'idProjeto'=>$idProjeto], [
                            'data' => [
                                'confirm' => 'Deseja realmente remover esta receita?',
                                'method' => 'post',
                            ],
                            'title' => Yii::t('yii', 'Remover Receita'),
                        ]);
                    }
                ],
                //['class' => 'yii\grid\ActionColumn'],
            ],
        ],
    ]); ?>

    


</div>
