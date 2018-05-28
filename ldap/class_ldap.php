<?php
class class_ldap{
	private $host;
	private $port;
	private $dn_login;
	private $dn_passw;
	private $dn_users;
	private $dn_group;
	private $dn_root;
	private $protocol_v3 = "s";
	private $ldapconn;
	public $ldap_results;
	public $ldap_results_conecta;
	
	function class_ldap($host, $port, $dn_login, $dn_passw, $dn_users, $dn_group, $dn_root, $protocol_v3){
	   $this->host = $host;
	   $this->port = $port;
	   $this->dn_login = $dn_login;
	   $this->dn_passw = $dn_passw;
	   $this->dn_users = $dn_users;
	   $this->dn_group = $dn_group;
	   $this->dn_root = $dn_root;
	   $this->protocol_v3 = $protocol_v3;
	}

	function conecta(){
		$ldapconn = ldap_connect($this->host, $this->port);
		if ($ldapconn) {
			if($this->protocol_v3 == "s"){
				ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
			}
			$ldapbind = ldap_bind($ldapconn, $this->dn_login, $this->dn_passw);
		}
		$this->ldapconn =  $ldapconn;
		if(isset($ldapbind)){
			if($ldapbind == 1){
				$this->ldap_results_conecta = "1";
			}
			else
			{
				$this->ldap_results_conecta = "0";
			}
		}
		else
		{
			$this->ldap_results_conecta = "0";
		}
		
	}
	
	function deconecta(){
		ldap_unbind($this->ldapconn);
	}

	function cria_usuario($dn_user_add, $user_attrib_array){
		ldap_add($this->ldapconn, $dn_user_add, $user_attrib_array);
	}
	
	function lista_usuarios($filter, $justthese, $getvalue="cn"){
		$ldapLista = ldap_list($this->ldapconn, $this->dn_users, $filter, $justthese);
		$entry = ldap_first_entry($this->ldapconn, $ldapLista);
		
		do {
		 	$values2 = ldap_get_values($this->ldapconn, $entry, $getvalue);
			$values[] = $values2['0'];
		 
		} while ($entry = ldap_next_entry($this->ldapconn, $entry));
		return $values;
		
	}
	
	function consulta_usuario($filter, $justthese){
		if($justthese=="*"){
			$ldapSearch = ldap_search($this->ldapconn, $this->dn_root, $filter);
		}
		else
		{
			$ldapSearch = ldap_search($this->ldapconn, $this->dn_root, $filter, $justthese);
		}
		return $this->ldap_results = ldap_get_entries($this->ldapconn, $ldapSearch);
	}

	function remove_usuario($remove_dn){
		if ($this->ldapconn) {
			$r = ldap_delete($this->ldapconn, $remove_dn);
		}
		return $r;
	}

	function cria_grupo($dn_grupo_add, $grupo_attrib_array){
		ldap_add($this->ldapconn, $dn_grupo_add, $grupo_attrib_array);
	}

	function lista_grupos($filter, $justthese){
		$ldapLista = ldap_list($this->ldapconn, $this->dn_group, $filter, $justthese);
		$entry = ldap_first_entry($this->ldapconn, $ldapLista);
		
		do {
			$values2 = ldap_get_values($this->ldapconn, $entry, "cn");
			$values[] = $values2['0'];
		 
		} while ($entry = ldap_next_entry($this->ldapconn, $entry));
		return $values;
	}
	
	function consulta_grupo($filter, $justthese){
		if($justthese=="*"){
			$ldapSearch = ldap_search($this->ldapconn, $this->dn_group, $filter);
		}
		else
		{
			$ldapSearch = ldap_search($this->ldapconn, $this->dn_group, $filter, $justthese);
		}
		return $this->ldap_results = ldap_get_entries($this->ldapconn, $ldapSearch);
	}

	function remove_grupo($remove_dn){
		if ($this->ldapconn) {
			$r = ldap_delete($this->ldapconn, $remove_dn);
		}
		return $r;
	}

	function modifica_atributos_do_objeto($dn_user_modify, $user_attrib_array){
		$retorno = ldap_modify($this->ldapconn, $dn_user_modify, $user_attrib_array);
		return $retorno;
	}

	function modifica_atributos_do_grupo($dn_user_modify, $user_attrib_array){
		$retorno = modifica_atributos_do_objeto($dn_user_modify, $user_attrib_array);
		return $retorno;
	}

	function modifica_atributos_do_usuario($dn_user_modify, $user_attrib_array){
		$retorno = modifica_atributos_do_objeto($dn_user_modify, $user_attrib_array);
		return $retorno;
	}
	
	function ver_foto($filter, $justthese){
		$sr=ldap_search($this->ldapconn, $this->dn_root, $filter, $justthese);
		$ei=ldap_first_entry($this->ldapconn, $sr);
		$data = ldap_get_values_len($this->ldapconn, $ei, "jpegphoto");
		return $this->ldap_results = $data['0'];
	}	
}
?>
