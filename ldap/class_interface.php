<?php
class class_interface{
	private $usuario_ad;
	private $senha_ad;
	
	//Metodo contrutor com cabecalho da interface
	function class_interface(){
		echo "\n \n \n \n \n \n E-TRUST - GERADOR DE BASES CSV E HTML DO AD \n \n";
	}
	
	function aviso_interface(){
		echo "Voce pode passar os parametros diremente ao executar este script. informando ARQUIVO USUARIO e SENHA \n";
	}
	//#Metodo de entrada de teclado em shell
	function entrada_teclado($frase){
		echo $frase;
		$handle = fopen ("php://stdin","r");
		$line = fgets($handle);
		
		if(trim(strtolower($line)) == 'sair' || trim(strtolower($line)) == 's'){
			echo "Saindo!\n";
			exit;
		}
		else
		{
			fclose($handle);
			unset($handle);
			$retorno = $line;
			return trim($retorno);
		}
	}

	//#Metodo para solicitacao de usuario.
	function solicitar_usuario(){
		$this->usuario_ad = $this->entrada_teclado("Informe o usuario de acesso ao AD ou digite sair: ");
		return $this->usuario_ad;
	}

	//#Metodo para solicitacao de senha.
	function solicitar_senha(){
		$this->senha_ad = $this->entrada_teclado("Informe a senha de acesso ao AD ou digite sair: ");
		return $this->senha_ad;
	}
}
?>