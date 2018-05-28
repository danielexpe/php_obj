<?php
include("/var/www/html/phps/PhpProject1/class_fila_onlinhe.php");

//Adicionando processos na Fila
$fila_processos = new class_fila_onlinhe();
$fila_processos->adicionar_fim_fila("cal 2008 -m 11");
$fila_processos->adicionar_fim_fila("cal 2009 -m 11");
$fila_processos->adicionar_fim_fila("cal 2010 -m 11");
$fila_processos->adicionar_fim_fila("cal 2011 -m 11");
$fila_processos->adicionar_fim_fila("cal 2012 -m 11");
$ll = chr(10).  chr(13);
//Rodando um loop da fila
echo 'Rodando processo.... '.$ll;
$fila_processos->rodar_processos_da_lista();
$fila_processos->exibir_processo_em_execucao();
echo $ll;
$fila_processos->exibir_resultado_do_processo();
echo 'Esperando timer.... '.$ll;
sleep("1");
echo 'Exibindo lista de processos.... '.$ll;
$fila_processos->exibir_lista_processos_para_execucao();
echo 'Excluindo processo key 2 ...'.$ll;
$fila_processos->excluir_da_fila('2');
echo 'Exibindo lista de processos.... '.$ll;
$fila_processos->exibir_lista_processos_para_execucao();
echo 'Excluindo processo key 0 ...'.$ll;
$fila_processos->excluir_da_fila('0');
echo 'Exibindo lista de processos.... '.$ll;
$fila_processos->exibir_lista_processos_para_execucao();
echo $ll;
echo 'Rodando processo.... '.$ll;
$fila_processos->rodar_processos_da_lista();
$fila_processos->exibir_processo_em_execucao();
echo $ll;
$fila_processos->exibir_resultado_do_processo();
$fila_processos->exibir_lista_processos_para_execucao();
$fila_processos->adicionar_fim_fila('cal 2015 -m 11');
$fila_processos->adicionar_fim_fila('cal 2016 -m 11');
echo $ll;
echo 'Exibindo lista de processos.... '.$ll;
$fila_processos->exibir_lista_processos_para_execucao();
echo $ll;
$fila_processos->adicionar_inicio_fila('cal 2020 -m 11');
echo $ll;
echo 'Exibindo lista de processos.... '.$ll;
$fila_processos->exibir_lista_processos_para_execucao();
echo $ll;
echo 'Rodando processo.... '.$ll;
$fila_processos->rodar_processos_da_lista();
$fila_processos->exibir_processo_em_execucao();
echo $ll;
$fila_processos->exibir_resultado_do_processo();

?>