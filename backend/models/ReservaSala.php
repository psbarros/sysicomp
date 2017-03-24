<?php

namespace app\models;

use Yii;

class ReservaSala extends \yii\db\ActiveRecord
{
    public $diasSemana;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_reservas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dataReserva', 'dataInicio', 'dataTermino', 'horaInicio', 'horaTermino', 'diasSemana'], 'safe'],
            [['sala', 'idSolicitante', 'atividade', 'dataInicio', 'dataTermino', 'horaInicio', 'horaTermino', 'tipo'], 'required'],
            [['sala', 'idSolicitante'], 'integer'],
            [['atividade'], 'string', 'max' => 50],
            [['tipo'], 'string', 'max' => 30],
            //[['dataInicio'], 'validarDataInicio'],
            [['horaInicio'], 'validarHoraInicio'],
            [['horaTermino'], 'validarHoraTermino'],
           // [['dataTermino'], 'validarDataTermino']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dataReserva' => 'Data da Reserva',
            'sala' => 'Sala',
            'idSolicitante' => 'Id Solicitante',
            'atividade' => 'Atividade',
            'tipo' => 'Tipo',
            'dataInicio' => 'Data de Início',
            'dataTermino' => 'Data de Término',
            'horaInicio' => 'Hora de Início',
            'horaTermino' => 'Hora de Término',
            'salaDesc.nome' => 'Sala',
            'solicitante.nome' => 'Solicitante da Reserva',
            'diasSemana' => 'Dias da Semana',
        ];
    }

    /*Funções para validação de atributos*/
    public function validarDataInicio($attribute, $params){
        if (!$this->hasErrors()) {
            if ($this->dataInicio < date('Y-m-d')) {
                $this->addError($attribute, 'Informe uma data igual ou posterior a '.date('d-m-Y'));
            }
        }
    }

    public function validarDataTermino($attribute, $params){
        if (!$this->hasErrors()) {
            if ($this->dataTermino < $this->dataInicio) {
                $this->addError($attribute, 'Informe uma data igual ou posterior a '.date("d-m-Y", strtotime($this->dataInicio)));
            }
        }
    }

    public function validarHoraTermino($attribute, $params){
        if (!$this->hasErrors()) {
            if ($this->dataInicio == $this->dataTermino && $this->horaTermino <= $this->horaInicio) {
                $this->addError($attribute, 'Informe uma data posterior a '.$this->horaInicio);
            }
        }
    }

    public function validarHoraInicio($attribute, $params){
        if (!$this->hasErrors()) {
            if ($this->horaInicio < '06:59:00') {
                $this->addError($attribute, 'Este horário está correto?');
            }
        }
    }

    public function getSalaDesc()
    {
        return $this->hasOne(Sala::className(), ['id' => 'sala']);
    }

    public function getSolicitante()
    {
        return $this->hasOne(User::className(), ['id' => 'idSolicitante']);
    }

    public function horarioOk(){
        $reservas = self::find()->where(['dataInicio' => $this->dataInicio])->andWhere(['sala' => $this->sala])->all();

        $this->horaTermino = $this->horaTermino == null ? date('H:i', strtotime('+29 minutes', strtotime($this->horaInicio))) : $this->horaTermino;

        if(count($reservas) == 0) return true;

        foreach ($reservas as $value) {
            if(!(($this->horaTermino < $value->horaInicio && $this->horaInicio < $value->horaInicio) ||
                ($this->horaInicio > $value->horaTermino && $this->horaTermino > $value->horaTermino)))
                return false;
        }

        return true;
    }

    public function beforeSave($insert){

        if (parent::beforeSave($insert)) {
            $this->dataInicio = date('Y-m-d', strtotime($this->dataInicio));
            $this->dataTermino =  date('Y-m-d', strtotime($this->dataTermino));
            return true;
        } else {
            return false;
        }
    }
}
