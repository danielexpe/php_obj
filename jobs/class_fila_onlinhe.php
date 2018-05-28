<?php
/**
 * Description of class_fila_onlinhe
 *
 * @author daniel
 */
class class_fila_onlinhe {
    private $processos_para_execucao = array();
    private $processo_executando;
    private $processos_executados = array();
    private $resultado_do_processo;
    
    function rodar_processos_da_lista(){
        if(!isset($this->processos_para_execucao[0])) { exit(0); }
        $this->processo_executando = $this->processos_para_execucao[0];
        $this->resultado_do_processo = shell_exec($this->processo_executando);
        $this->processo_executado[] = $this->processo_executando;
        class_fila_onlinhe::ir_para_proximo_processo();
        return "Rodado proximo processo da fila \n";
    }
    
    function rodar_processos_da_lista_auto(){
        if(!isset($this->processos_para_execucao[0])) { exit(0); }
        $this->processo_executando = $this->processos_para_execucao[0];
        $this->resultado_do_processo = shell_exec($this->processo_executando);
        $this->processo_executado[] = $this->processo_executando;
        unset($this->processos_para_execucao[0]);
        $temp_array = array();
        foreach ($this->processos_para_execucao as $key => $value) {
            $temp_array[] = $value;
        }
        $this->processos_para_execucao = $temp_array;
        $this->exibir_lista_processos_para_execucao();
        sleep("10");
        class_fila_onlinhe::rodar_processos_da_lista_auto();
    }
    
    function exibir_lista_processos_para_execucao(){
        //print_r($this->processos_para_execucao);
        return $this->array_to_string($this->processos_para_execucao)."\n";
    }
    
    function exibir_lista_processos_executados(){
        //print_r($this->processos_executados);
        return $this->array_to_string($this->processos_executados)."\n";
    }
    
    function exibir_processo_em_execucao(){
        //echo $this->processo_executando;
        return $this->processo_executando."\n";
    }
    
    function exibir_resultado_do_processo(){
        //echo $this->resultado_do_processo;
        return $this->resultado_do_processo."\n";
    }
    
    function adicionar_fim_fila($cmd){
        $this->processos_para_execucao[] = $cmd;
        return "Adicionado ao fim da fila com sucesso \n";
    }
    
    function adicionar_inicio_fila($cmd){
        array_unshift($this->processos_para_execucao, $cmd);
        return "Adicionado ao inicio da fila com sucesso \n";
    }
    
    function ir_para_proximo_processo(){
        unset($this->processos_para_execucao[0]);
        $temp_array = array();
        foreach ($this->processos_para_execucao as $value) {
            $temp_array[] = $value;
        }
        $this->processos_para_execucao = $temp_array;
        return "Avançado para o proximo processo com sucesso \n";
    }
    
    function excluir_da_fila($processo){
        unset($this->processos_para_execucao[$processo]);
        foreach ($this->processos_para_execucao as $value) {
            $temp_array[] = $value;
        }
        $this->processos_para_execucao = $temp_array;
        return "Processo excluido da fila com sucesso \n";
    }
    
    function array_to_string($array){
        $var_string = "";
        foreach ($array as $key => $value) {
            $var_string .= "\n".$key." -> ".$value;
        }
        return $var_string."\n";
    }
    
    function salvar_listas_em_armazenamento(){
        $handle = fopen('lista_de_processos_para_rodar.csv', 'w');
        foreach ($this->processos_para_execucao as $value) {
           fwrite($handle, $value."\n");
        }
        fclose($handle);
        return "Lista Salva com sucesso. \n";
    }
    
    function carregar_listas_de_armazenamento(){
        if( $handle = fopen('lista_de_processos_para_rodar.csv', 'r') ){
            while (!feof($handle)){
                $linha_atual = trim(fgets($handle,9999));
                if($linha_atual != ""){
                    $this->processos_para_execucao[] = trim(fgets($handle,9999));
                }
            }
        }
        fclose($handle);
        return "Lista carregada com sucesso. \n";
    }
}
