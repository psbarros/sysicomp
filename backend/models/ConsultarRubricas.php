<?php
namespace backend\models;

use yii\base\Model;

class ColsultarSaldoForm  extends \yii\db\ActiveRecord
{
    public $rubrica_id;
    public $valor_disponivel;

    public function rules()
    {
        return[
            [['rubrica_id'], 'integer'],
            [['valor_disponivel'], 'number'],
        ];
    }

    function attributeLabels()
    {
        return [
            'rubrica_id' => 'Rubrica',
            'valor_disponivel' => 'Saldo',
        ];
    }

}
?>