<?php

$_horas = ['07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00'];
$_dias = ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'];
$_matriz_horarios = [];

for ($dia=0, $diaatual = $inicio;$dia<count($_dias);$dia++, $diaatual = date('Y-m-d',strtotime("+1 day", strtotime($diaatual)))) {
    for ($hora=0;$hora<count($_horas);$hora++) {
        $_matriz_horarios[$dia][$hora] = 0;
    }
}

for ($dia=0, $diaatual = $inicio;$dia<count($_dias);$dia++, $diaatual = date('Y-m-d',strtotime("+1 day", strtotime($diaatual)))) {
    for ($hora=0;$hora<count($_horas);$hora++) {
        foreach ($reservas as $reserva) {
            if ((strtotime($reserva->dataInicio) == strtotime($diaatual)) && (strtotime($reserva->horaInicio) >= strtotime($_horas[$hora].':00')) &&  (strtotime($reserva->horaInicio) < strtotime($_horas[$hora+1].':00'))) {
                $qtd_horas = round((strtotime($reserva->horaTermino) - strtotime($reserva->horaInicio))/3600);
                for ($i=0; $i<$qtd_horas; $i++) {
                    $_matriz_horarios[$dia][$hora+$i] = $qtd_horas.":".$reserva->atividade;
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <div style="width: 100%">
            <div style="float:left;width: 35%">
                <h3>Instituto de Computação</h3>
            </div>
            <div style="float:right;width: 60%; text-align: right">
                <h3><?= $sala->nome ?> &mdash; Horários</h3>
            </div>
        </div>
        <table style="border: 1px solid black; width: 100%; border-collapse: collapse;">
            <tr>
                <th style="border: 1px solid black; width: 10mm; align: center"></th>
                <?php for ($dia=0;$dia<count($_dias);$dia++): ?>
                    <th style="border: 1px solid black; align: center"><center><?= $_dias[$dia] ?></center></th>
                <?php endfor; ?>
            </tr>
            <?php for ($hora=0;$hora<count($_horas);$hora++): ?>
                <tr>
                    <td style="border: 1px solid black; height: 10mm; width: 10mm; align: left">&nbsp;<?= $_horas[$hora] ?>&nbsp;</td>
                    <?php

                    for ($dia=0;$dia<count($_dias);$dia++) {
                        if ($_matriz_horarios[$dia][$hora] == 0) {
                            echo '<td style="border: 1px solid #DDDDDD; align: center"></td>' . "\n";
                        }
                        elseif ($_matriz_horarios[$dia][$hora] != $_matriz_horarios[$dia][$hora-1]) {
                            $dados = explode(":",$_matriz_horarios[$dia][$hora]);
                            echo '<td style="border: 1px solid #DDDDDD; align: center" rowspan="' . $dados[0] . '">' . $dados[1] . '</td>' . "\n";
                        }
                    }

                    ?>
                </tr>
            <?php endfor; ?>
        </table>
    </body>
</html>
