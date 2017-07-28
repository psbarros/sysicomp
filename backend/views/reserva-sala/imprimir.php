<?php

$_horas = ['07:00','07:30','08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30','20:00','20:30','21:00','21:30','22:00','22:30'];
$_dias = ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'];
$_matriz_horarios = [];

for ($dia=0;$dia<count($_dias);$dia++) {
    for ($hora=0;$hora<count($_horas);$hora++) {
        $_matriz_horarios[$dia][$hora] = 0;
    }
}

for ($dia=0, $diaatual = $inicio;$dia<count($_dias);$dia++, $diaatual = date('Y-m-d',strtotime("+1 day", strtotime($diaatual)))) {
    for ($hora=0;$hora<count($_horas);$hora++) {
        foreach ($reservas as $reserva) {
            if ((strtotime($reserva->dataInicio) == strtotime($diaatual)) && (strtotime($reserva->horaInicio) >= strtotime($_horas[$hora].':00')) &&  (strtotime($reserva->horaInicio) < strtotime($_horas[$hora+1].':00'))) {
                $qtd_horas = max(round((strtotime($reserva->horaTermino) - strtotime($reserva->horaInicio))/1800),2);
                for ($i=0; $i<$qtd_horas; $i++) {
                    $_matriz_horarios[$dia][$hora+$i] = $qtd_horas."#".$reserva->atividade."#".$reserva->horaInicio."#".$reserva->horaTermino;
                }
            }
        }
    }
}

if ($ver_matriz) {
    echo "<table>";
    echo '<th style="border: 1px solid black; width: 10mm; align: center"></th><th style="border: 1px solid black; align: center"><center>Dom</center></th><th style="border: 1px solid black; align: center"><center>Seg</center></th><th style="border: 1px solid black; align: center"><center>Ter</center></th><th style="border: 1px solid black; align: center"><center>Qua</center></th><th style="border: 1px solid black; align: center"><center>Qui</center></th><th style="border: 1px solid black; align: center"><center>Sex</center></th><th style="border: 1px solid black; align: center"><center>Sáb</center></th></tr>';
    for ($hora=0;$hora<count($_horas);$hora++) {
        echo "<tr>";
        echo "<td>" . $_horas[$hora] . "</td>";
        for ($dia=0;$dia<count($_dias);$dia++) {
            echo "<td>" . $_matriz_horarios[$dia][$hora] . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
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
        <table style="border: 1px solid black; width: 100%; height: 1000mm; border-collapse: collapse; border-spacing:10px">
            <tr>
                <th style="border: 1px solid black; width: 10mm; align: center"></th>
                <?php for ($dia=1;$dia<count($_dias);$dia++): ?>
                    <th style="border: 1px solid black; align: center; width: 45mm"><center><?= $_dias[$dia] ?></center></th>
                <?php endfor; ?>
            </tr>
            <?php for ($hora=0;$hora<count($_horas);$hora++): ?>
                <tr>
                    <?php if ($hora%2==0): ?>
                    <td style="border: 1px solid black; width: 10mm; max-width: 10mm; align: left" rowspan="2"><div style="font-size: 2mm">&nbsp;</div>&nbsp;<?= $_horas[$hora] ?>&nbsp;<div style="font-size: 2.5mm">&nbsp;</div></td>
                    <?php endif; ?>
                    <?php

                    for ($dia=1;$dia<count($_dias);$dia++) {
                        if ($_matriz_horarios[$dia][$hora] == 0) {
                            echo '<td style="border: 1px solid #DDDDDD; align: center"></td>' . "\n";
                        }
                        elseif (($hora==0) || ($_matriz_horarios[$dia][$hora] != $_matriz_horarios[$dia][$hora-1])) {
                            $dados = explode("#",$_matriz_horarios[$dia][$hora]);
                            echo '<td class="td-content" style="background-color: #eee; font-size: 12px; padding: 0px 10px 0px 10px; margin: 0; align: center" rowspan="' . $dados[0] . '">';
                            echo "<span style='font-size: 10px'>" . substr($dados[2],0,-3) ." &mdash; ". substr($dados[3],0,-3) ."</span><br><strong>".$dados[1]."</strong>";
                            echo "</td>\n";
                        }
                    }

                    ?>
                </tr>
            <?php endfor; ?>
        </table>
    </body>
</html>
