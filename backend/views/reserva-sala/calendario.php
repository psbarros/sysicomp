<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\JsExpression;
use yii\bootstrap\Modal;

$this->title = 'Reserva: '.$modelSala->nome;
$this->params['breadcrumbs'][] = ['label' => 'Reserva de Sala', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
<script type="text/javascript">

        function anoSelecionado() {
            var x = document.getElementById("comboBoxAno").value;
            window.location="index.php?r=reserva-sala/calendario&idSala="+x;
        }

        function imprimir() {


            var toPrint = document.getElementsByClassName('fc-body')[0].cloneNode(true);



            var linkElements = document.getElementsByTagName('link');
            var link = '';
            for(var i = 0, length = linkElements.length; i < length; i++) {
              link = link + linkElements[i].outerHTML;
            }

            var styleElements = document.getElementsByTagName('style');
            var styles = '';
            for(var i = 0, length = styleElements.length; i < length; i++) {
              styles = styles + styleElements[i].innerHTML;
            }

            var popupWin = window.open('', '_blank');
            popupWin.document.open();
            popupWin.document.write('<html><head><title>Schedule Preview</title>'+link+'<style>'+styles+'</style></head><body">');
            popupWin.document.write(toPrint.innerHTML);
            popupWin.document.write('<script type="text/javascript">window.print();<'+'/script>');
            popupWin.document.write('</body></html>');
            popupWin.document.close();
            //setTimeout(popupWin.print(), 60000);
            return false;
        }
</script>
  <p>
    <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar ', ['index'], ['class' => 'btn btn-warning']) ?>
    <?= Html::a('<span class="glyphicon glyphicon-list"></span> Listagem ', ['reserva-sala/listagemreservas'], ['class' => 'btn btn-warning']) ?>
    <?= Html::a('<span class="glyphicon glyphicon-list"></span> Imprimir ', ['#'], ['class' => 'btn btn-warning','onclick' => 'return imprimir()']) ?>
  </p>
  <?php
    Modal::begin([
      'header' => '<h2>Reserva de Sala</h2>',
      'id' => 'modal',
      'size' => 'modal-lg',
    ]);

    echo "<div id='modalContent'></div>";

    Modal::end();
  ?>

  <p>
    <b>Selecione uma Sala:</b> <select id= "comboBoxAno" onchange="anoSelecionado();" class="form-control" style="margin-bottom: 20px; width:20%;">
        <?php for($i=0; $i<count($modelSalas); $i++){

            $valores = $modelSalas[$i]->id;

            ?>
            <option value='<?= $modelSalas[$i]->id ?>' <?php if($modelSalas[$i]->id == $_GET["idSala"]){echo "SELECTED";} ?> > <?php echo $modelSalas[$i]->nome ?> </option>
        <?php } ?>
    </select>
	</p>

	<p>Clique em uma reserva para visualizá-la ou clique em um dia e horário para criar uma reserva</p>


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
