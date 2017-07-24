<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\JsExpression;
use yii\bootstrap\Modal;

$this->title = 'Reserva: '.$modelSala->nome;
$this->params['breadcrumbs'][] = ['label' => 'Reserva de Sala', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss("
@page
{
    size: auto;   /* auto is the initial value */
    margin: 0mm;  /* this affects the margin in the printer settings */
}
");

?>
<div>


  <?php
    Modal::begin([
      'header' => '<h2>Reserva de Sala</h2>',
      'id' => 'modal',
      'size' => 'modal-lg',
    ]);
    Modal::end();
  ?>

      <?= \yii2fullcalendar\yii2fullcalendar::widget(array(
          'events'=> $reservasCalendario,
          'clientOptions' => [
            'allDayDefault' => false,
            'weekends' => true,
            'defaultView' => 'agendaWeek',
            'slotDuration' => '00:30:00',
            'minTime' => '07:00:00',
            'maxTime' => '23:00:00',
            'height' => 'auto',
            'allDaySlot' => false,
            'slotLabelFormat' => 'H:mm',
            'dayClick' => new JsExpression("function(date, jsEvent, view) {
              var dateStr = date;
              var data = (new Date(dateStr)).toISOString().slice(0, 10);
              var hora = (new Date(dateStr)).toISOString().slice(11, 16);
              $.get('index.php?r=reserva-sala/create', {'sala': '$modelSala->id', 'dataInicio': data,'horaInicio': hora, 'requ': 'AJAX'}, function(data){
                  $('#modal').modal('show')
                  .find('#modalContent')
                  .html(data);
              });

            }"),
            'eventClick' => new JsExpression("function(calEvent, jsEvent, view) {
              window.location.href = 'index.php?r=reserva-sala/view&id='+calEvent.id;
            }"),
        ],
      ));
  ?>

</div>
