<?php 
include("class_ldap.php");
include("class_interface.php");
include("class_arquivos_saida.php");

$interface = new class_interface(); //Chama interface shell com usuario
//#Se nao foi informado login e senha.
if(!isset($argv[2])){
	$interface->aviso_interface();
	$usuario_ad = $interface->solicitar_usuario();
	$senha_ad = $interface->solicitar_senha();
}
//#Se nao foi informada senha.
elseif(!isset($argv[3])){
	$interface->aviso_interface();
	$usuario_ad = $argv[2];
	$senha_ad = $interface->solicitar_senha();
}
//#Se foi informado login e senha por argumento
else
{
	$usuario_ad = $argv[2];
	$senha_ad = $argv[3];
}
$arquivo_entrada = $argv[1];

function consulta_grupo_ad($linha_array, $usuario_ad, $senha_ad){
	//#VARIAVEIS FIXAS PARA CONEXAO LDAP
	$port = "389";//Inserir aqui a porta aberta para conexoes
	$protocol_v3 = "s";
	$filter = "objectclass=group";
	$justthese = array('dn', 'cn', 'member','objectclass','SAMAccountName','name');

	if(isset($linha_array[2])){ //Trata possivel linha em branco no arquivo de entrada
		$obj_ldap = new class_ldap($linha_array[1], $port, $usuario_ad, $senha_ad, $linha_array[2], $linha_array[2], $linha_array[2], $protocol_v3);
		$obj_ldap->conecta();
		if(!$obj_ldap->ldap_results_conecta){
			echo "Nãfoi possivel conectar ao LDAP AD!";
			exit;
		}
		$resultado = $obj_ldap->consulta_grupo($filter, $justthese);
		
		//######GERA ARRAY NO FORMATO PARA SER EXPORTADO PARA ARQUIVO
		for($x=0; $x<count($resultado); $x++){
			if(isset($resultado[$x])){ //Trata erro de chaves nao existentes
				if(isset($resultado[$x]['member'])){
					for($y=0; $y<(count($resultado[$x]['member']) -1); $y++){
					
						//#Consulta no LDAP os atributos dos menbros encontrados nos grupos
						$container_do_membro_array = explode(",", $resultado[$x]['member'][$y]);
						$container_do_membro="";
						for($kz=1; $kz<count($container_do_membro_array); $kz++){
							$container_do_membro .= $container_do_membro_array[$kz].",";
						}
						$container_do_membro = substr(trim($container_do_membro), 0, -1); //container onde esta o membro
						
						$obj_ldap_menber = new class_ldap($linha_array[1], $port, $usuario_ad, $senha_ad, $container_do_membro, $container_do_membro, $container_do_membro, $protocol_v3);
						$obj_ldap_menber->conecta();
						$justthese = array('dn', 'cn', 'member','objectclass','SAMAccountName','UserAccountControl','name');
						$resultado2 = $obj_ldap_menber->consulta_grupo($container_do_membro_array[0], $justthese);
						
						$item_saida['DN_Grupo'] = $linha_array[2];
						$item_saida['SAMAccountName'] = $resultado2[0]['samaccountname'][0];
						$item_saida['Group'] = $resultado[$x]['name'][0];
						$item_saida['User'] = $resultado2[0]['name'][0];
						$tipo_usuario = 0;
						
						for($za=0; $za<(count($resultado2[0]['objectclass']) -1); $za++){
							if( $resultado2[0]['objectclass'][$za] == "user" )	{ $tipo_usuario = 1; }
						}
						if($tipo_usuario == 1){  //#Se o membro encontrado for um usuario.
							$item_saida['Tipo'] = "Usuario"; //Tipo
							if( isset($resultado2[0]['UserAccountControl']) && $resultado2[0]['UserAccountControl'] == "514")
							{
								$item_saida['Status'] = "Bloqueado"; //Status
							} else
							{
								$item_saida['Status'] = "Ativo"; //Status
							}
						}
						else //Se o membro encontrado for um grupo.
						{
							$item_saida['Tipo'] = "Grupo"; //Tipo
							$item_saida['Status'] = ""; //Status
							$grupos_filho[] = '"'.$linha_array[0].'","'.$linha_array[1].'","'.$resultado2[0]['dn'].'"'; //Grupos filhos para recursao
						}
						$item_saida['DN_Objeto'] = $resultado2[0]['dn']; //DN Objeto
						
						$arquivo_saida[] = $item_saida;
						unset($item_saida);
					}
				}
				else //Se grupo nao contem membros
				{
					$item_saida['DN_Grupo'] = $linha_array[2];
					$item_saida['DN_Objeto'] = 0;
					$arquivo_saida[] = $item_saida;
					unset($item_saida);
				}
				
				$r_saida = $arquivo_saida;
				unset($arquivo_saida);
			}
		}
		//##########
		$obj_ldap->deconecta();
		unset($obj_ldap);
	}
	
	if(isset($r_saida)){
		$s_saida['saida'] = $r_saida;
	} 
	else
	{
		$s_saida['saida'] = "0";
	}
	
	if(isset($grupos_filho)){
		$s_saida['grupos_filho'] = $grupos_filho;
	}
	else
	{
		$s_saida['grupos_filho'] = 0;
	}
	unset($grupos_filho);
	return ($s_saida);
}

//#Carrega o arquivo com os dominios e ips correspondentes
$arquivo_entrada_dominios_ips = "dominios_ips.txt";
$dominios_ips = array();
if (file_exists($arquivo_entrada_dominios_ips)){
	$saida = array();
	$handle = fopen ($arquivo_entrada_dominios_ips,"r");
	while (!feof($handle)) //Loop ate o final do arquivo de entrada
	{ 
		$linha = fgets($handle, 256);
		$linha_array = explode('","', substr(trim($linha), 1, -1)); //Retira os espacos vazios (trim), descarta primeiro e ultimo cracter '"'( substr), transforma em array (explode).  
		if(isset($linha_array[1])){ 	
			$dominios_ips[$linha_array[1]] = $linha_array[0];
		}
	}
	fclose($handle);
}

//#Abrir o arquivo txt se existir no caminho informado
if (file_exists($arquivo_entrada)){
	$saida = array();
	$handle = fopen ($arquivo_entrada,"r");
	while (!feof($handle)) //Loop ate o final do arquivo de entrada
	{ 
		$linha = fgets($handle, 256);
		$linha_array = explode('","', substr(trim($linha), 1, -1)); //Retira os espacos vazios (trim), descarta primeiro e ultimo cracter '"'( substr), transforma em array (explode).  
		
		if(isset($linha_array[2])){
			$all_users[$linha_array[2]]['dn'] = $linha_array[2]; //Array com DNs do arquivo de entrada
		}
		
		$saidat = consulta_grupo_ad($linha_array, $usuario_ad, $senha_ad);
		if($saidat['grupos_filho'] != "0"){
			$grupos_filho = $saidat['grupos_filho'];
			unset($saidat['grupos_filho']);
		}
		if($saidat['saida'] != "0"){
			$saida[] = $saidat['saida'];
			
			for($xf=0; $xf<count($saidat['saida']); $xf++){
				if(isset($saidat['saida'][$xf]['Tipo']) && $saidat['saida'][$xf]['Tipo'] == "Grupo"){
					$all_users[$linha_array[2]]['grupos'][] = $saidat['saida'][$xf]['DN_Objeto']; //Array com DNs do arquivo de entrada
				}
				elseif(isset($saidat['saida'][$xf]['Tipo']) && $saidat['saida'][$xf]['Tipo'] == "Usuario")
				{
					//$all_users[$linha_array[2]]['usuarios'][] = $saidat['saida'][$xf]['DN_Objeto']; //Array com DNs do arquivo de entrada
					$all_users[$linha_array[2]]['usuarios'][] = $saidat['saida'][$xf]; //Array com DNs do arquivo de entrada
				}
			}
			unset($saidat['saida']);
		}
	}
	fclose($handle);
	
	$xy = 0;
	if(isset($grupos_filho)){ //Se estiver setado algum grupo filho para ser verificado.
		while(is_array($grupos_filho) && $xy < 5){
		
			$grupos_filho_recursivo = $grupos_filho; //carrega em $grupos_filho_recursivo o conteudo de $grupos_filho
			unset($grupos_filho); //unset em $grupos_filho
			
			for($xd=0; $xd<count($grupos_filho_recursivo); $xd++){ //executa procedimento em todas posicoes de $grupos_filho_recursivo
				
				$grp_array = explode('","', substr(trim($grupos_filho_recursivo[$xd]), 1, -1)); //Transforma linha corrente em array
				$ip_dominio = 0;
				$chaves_dominios_ips = array_keys($dominios_ips);
				
			 	for($xh=0; $xh<count($dominios_ips); $xh++){
					$pos = strrpos($grp_array[2], $chaves_dominios_ips[$xh]);
					print_r($pos);
					if ($pos !== false) { 
						$ip_dominio = $dominios_ips[$chaves_dominios_ips[$xh]];	
					}
				}	

				if($ip_dominio != "0"){
					$grp_array[1] = $ip_dominio;
				}

				$saidat = consulta_grupo_ad($grp_array, $usuario_ad, $senha_ad); //Consulta grupo da linha corrente
				
				if($saidat['grupos_filho'] != "0"){ //Se tiver grupo membro em Saidat
					$grupos_filho = $saidat['grupos_filho']; //carrega grupo em $grupos_filho para proximo loop
					unset($saidat['grupos_filho']); //limpa $saidat['grupos_filho']
				}
				if($saidat['saida'] != "0"){ //Se tiver retorno de saida incrementa array para ser direcionado para saida em arquivos html e csv
					$saida[] = $saidat['saida']; //carrega registro em $saida[] para ser direcionado para saida em arquivos html e csv
					//$all_users['saida'][] = $saidat['saida'];

					$chaves_all_users = array_keys($all_users);
					for($xh=0; $xh<count($all_users); $xh++){
						if(isset($all_users[$chaves_all_users[$xh]]['grupos'])){
							for($xi=0; $xi<count($all_users[$chaves_all_users[$xh]]['grupos']); $xi++){
								
								for($xj=0; $xj<count($saidat['saida']); $xj++){
									if($saidat['saida'][$xj]['DN_Grupo'] == $all_users[$chaves_all_users[$xh]]['grupos'][$xi]){
										if($saidat['saida'][$xj]['Tipo'] == "Usuario"){
											//$all_users[$chaves_all_users[$xh]]['usuarios'][] = $saidat['saida'][$xj]['DN_Objeto'];
											$all_users[$chaves_all_users[$xh]]['usuarios'][] = $saidat['saida'][$xj];
										}
										else
										{
											$grupo_recursivo[] = $saidat['saida'][$xj]['DN_Objeto'];
										}
									}
								}
							}
							if(isset($all_users[$chaves_all_users[$xh]]['grupos'])){
								unset($all_users[$chaves_all_users[$xh]]['grupos']);
							}
							if(isset($grupo_recursivo)){
								$all_users[$chaves_all_users[$xh]]['grupos'] = $grupo_recursivo;
								unset($grupo_recursivo);
							}
						}
					}
					unset($saidat['saida']); //limpa $saidat['saida']
				}
			}
			$xy++;
		}
	}
	$gera_arquivos_saidas = new class_arquivos_saida($saida);
	$gera_arquivos_saidas->gerar_saida_csv();
	$gera_arquivos_saidas->gerar_saida_html();
	$gera_arquivos_saidas->gerar_saida_csv_allusers($all_users);
	unset($gera_arquivos_saidas);
}
else
{
	echo "Arquivo nao encontrado. \n Saindo! \n";
}

?>

