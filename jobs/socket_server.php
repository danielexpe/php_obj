#!/usr/local/bin/php -q
<?php
error_reporting(E_ALL);
include("/var/www/html/phps/PhpProject1/class_fila_onlinhe.php");
//Adicionando processos na Fila
$fila_processos = new class_fila_onlinhe();
$fila_processos->carregar_listas_de_armazenamento();
//$fila_processos->rodar_processos_da_lista();


/* Allow the script to hang around waiting for connections. */
set_time_limit(0);

/* Turn on implicit output flushing so we see what we're getting
 * as it comes in. */
ob_implicit_flush();

$address = '192.168.25.100';
$port = 10000;

if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
}

if (socket_bind($sock, $address, $port) === false) {
    echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
}

if (socket_listen($sock, 5) === false) {
    echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
}

do {
    if (($msgsock = socket_accept($sock)) === false) {
        echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
        break;
    }
    /* Send instructions. */
    $msg = "\nBem vindo ao Servidor de processos. \n" .
        "Digite alguma das opções abaixo: \n \n" .
        "Para exibir lista de processos, digite 'exibir' \n" .
        "Para salvar listas em arquivos, digite 'salvar' \n" .
        "Para carregar listas dos arquivos, digite 'carregar' \n" .
        "Para rodar proximo processo, digite 'rodar_proximo' \n" .
        "Para ver este menu, digite 'ajuda'. \n".    
        "Para Sair, digite 'quit'. \n". 
        "Para parar o servidor digite 'shutdown'.\n";
    socket_write($msgsock, $msg, strlen($msg));

    do {
        if (false === ($buf = socket_read($msgsock, 2048, PHP_NORMAL_READ))) {
            echo "socket_read() failed: reason: " . socket_strerror(socket_last_error($msgsock)) . "\n";
            break 2;
        }
        if (!$buf = trim($buf)) {
            continue;
        }
        if ($buf == 'exibir') {
            $talkback = $fila_processos->exibir_lista_processos_para_execucao();
            socket_write($msgsock, $talkback, strlen($talkback));
            continue;
        }
        if ($buf == 'salvar') {
            $talkback = $fila_processos->salvar_listas_em_armazenamento();
            socket_write($msgsock, $talkback, strlen($talkback));
            continue;
        }
        if ($buf == 'carregar') {
            $talkback = $fila_processos->carregar_listas_de_armazenamento();
            socket_write($msgsock, $talkback, strlen($talkback));
            continue;
        }
        if ($buf == 'rodar_proximo') {
            $talkback = $fila_processos->rodar_processos_da_lista();
            socket_write($msgsock, $talkback, strlen($talkback));
            continue;
        }
        if ($buf == 'ajuda') {
            socket_write($msgsock, $msg, strlen($msg));
            continue;
        }
        if ($buf == 'quit') {
            break;
        }
        if ($buf == 'shutdown') {
            socket_close($msgsock);
            break 2;
        }
        $talkback = "PHP: You said '$buf'.\n";
        socket_write($msgsock, $talkback, strlen($talkback));
        echo "$buf\n";
    } while (true);
    socket_close($msgsock);
} while (true);

socket_close($sock);
?>
