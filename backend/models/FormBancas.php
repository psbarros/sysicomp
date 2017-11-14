<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ContProjTransferenciasSaldoRubricas;

/**
 * This is the model class for table "j17_banca_has_membrosbanca".
 *
 * @property integer $banca_id
 * @property integer $membrosbanca_id
 * @property string $funcao
 * @property string $passagem

 */





class FormBancas extends \yii\db\ActiveRecord
{


public $membrosbanca_id_1;
public $membrosbanca_id_2;
public $membrosbanca_id_3;
public $membrosbanca_id_4;
public $membrosbanca_id_5;
public $funcao1;
public $funcao2;
public $funcao3;
public $funcao4;
public $funcao5;
public $tipobanca;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_banca_has_membrosbanca';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['membrosbanca_id_1', 'membrosbanca_id_2', 'membrosbanca_id_3', 'membrosbanca_id_4', 'membrosbanca_id_5'], 'integer'],

            [['membrosbanca_id_1', 'membrosbanca_id_2', 'membrosbanca_id_3'],'required',

            'when' => function($model){
                return $model->tipobanca!=4;
            },
            'whenClient' => "function (attribute, value) {

                return $('#membrosObrigatorios').val() == 1;
            }"],

            [['membrosbanca_id_1', 'membrosbanca_id_2', 'membrosbanca_id_3', 'membrosbanca_id_4', 'membrosbanca_id_5'], 'required',
            'when' => function($model){
                return $model->tipobanca==4;
            },
            'whenClient' => "function (attribute, value) {

                return $('#membrosObrigatorios').val() == 0;
            }"],




            [['funcao1', 'funcao2', 'funcao3', 'funcao4', 'funcao5','tipobanca'], 'string'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'banca_id' => 'Banca',
            'membrosbanca_id_1' => 'membro id 1',
            'funcao1' => 'funcao1',
            'membrosbanca_id_2' => 'membro id 2',
            'funcao2' => 'funcao2',
            'membrosbanca_id_3' => 'membro id 3',
            'funcao3' => 'funcao3',
            'membrosbanca_id_4' => 'membro id 4',
            'funcao4' => 'funcao4',
            'membrosbanca_id_5' => 'membro id 5',
            'funcao5' => 'funcao5',
            'tipobanca' => 'tipo da banca',
        ];
    }

    public function getMembrosBanca()
    {
        return $this->hasOne(MembrosBanca::className(), ['id'=>'membrosbanca_id_1']);
    }

    public function getMembrosBanca2()
    {
        return $this->hasOne(MembrosBanca::className(), ['id'=>'membrosbanca_id_2']);
    }

    public function getMembrosBanca3()
    {
        return $this->hasOne(MembrosBanca::className(), ['id'=>'membrosbanca_id_3']);
    }

    public function getMembrosBanca4()
    {
        return $this->hasOne(MembrosBanca::className(), ['id'=>'membrosbanca_id_4']);
    }

    public function getMembrosBanca5()
    {
        return $this->hasOne(MembrosBanca::className(), ['id'=>'membrosbanca_id_5']);
    }



}

