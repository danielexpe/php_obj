<?php
class class_arquivos_saida{
	private $entrada_array;
	
	function class_arquivos_saida($entrada_array){
		$this->entrada_array = $entrada_array;
	}
	
	function gerar_saida_csv(){
		for($xb=0; $xb<count($this->entrada_array); $xb++){
			$nome_arq_dn = str_replace("," , "_",  $this->entrada_array[$xb]['0']['DN_Grupo']);
			$arquivo_csv = "csv_".date('Y-m-d_h_i_s')."_".$nome_arq_dn.".csv";
			$Handle = fopen($arquivo_csv, 'w');
			$csv_cabecalho = '"DN Grupo","SAMAccountName","Group","User","Tipo","Status","DN Objeto"'.chr(13).chr(10);
			fwrite($Handle, $csv_cabecalho); 
			
			for($xc=0; $xc<count($this->entrada_array[$xb]); $xc++){
				if($this->entrada_array[$xb][$xc]['DN_Objeto'] != "0"){
					$cvs_lista = '"'.$this->entrada_array[$xb][$xc]['DN_Grupo'].'","'.$this->entrada_array[$xb][$xc]['SAMAccountName'].'","'.$this->entrada_array[$xb][$xc]['Group'].'","'.$this->entrada_array[$xb][$xc]['User'].'","'.$this->entrada_array[$xb][$xc]['Tipo'].'","'.$this->entrada_array[$xb][$xc]['Status'].'","'.$this->entrada_array[$xb][$xc]['DN_Objeto'].'"'.chr(13).chr(10);
					fwrite($Handle, $cvs_lista);
				}
			}
			fclose($Handle); 
		}
	}
	
	function gerar_saida_html(){
		for($xb=0; $xb<count($this->entrada_array); $xb++){
			$nome_arq_dn = str_replace("," , "_",  $this->entrada_array[$xb]['0']['DN_Grupo']);
			$arquivo_html = "html_".date('Y-m-d_h_i_s')."_".$nome_arq_dn.".html";
			$Handle = fopen($arquivo_html, 'w');
			$html_cabecalho = "<table border='1'>".chr(13).chr(10);
			$html_cabecalho .= "	<tr><td>DN Grupo</td><td>SAMAccountName</td><td>Group</td><td>User</td><td>Tipo</td><td>Status</td><td>DN Objeto</td></tr>".chr(13).chr(10);
			fwrite($Handle, $html_cabecalho); 
			
			for($xc=0; $xc<count($this->entrada_array[$xb]); $xc++){
				if($this->entrada_array[$xb][$xc]['DN_Objeto'] != "0"){
					$cvs_lista = '<tr><td>'.$this->entrada_array[$xb][$xc]['DN_Grupo'].'</td><td>'.$this->entrada_array[$xb][$xc]['SAMAccountName'].'</td><td>'.$this->entrada_array[$xb][$xc]['Group'].'</td><td>'.$this->entrada_array[$xb][$xc]['User'].'</td><td>'.$this->entrada_array[$xb][$xc]['Tipo'].'</td><td>'.$this->entrada_array[$xb][$xc]['Status'].'&nbsp;</td><td>'.$this->entrada_array[$xb][$xc]['DN_Objeto'].'</td><td></tr>'.chr(13).chr(10);
					fwrite($Handle, $cvs_lista);
				}
			}
			$cvs_lista = "</table>".chr(13).chr(10);
			fwrite($Handle, $cvs_lista);
			fclose($Handle); 
		}
	}
	
	function gerar_saida_csv_allusers($all_users){
		$chaves_allusers = array_keys($all_users);
		
		for($xb=0; $xb<count($all_users); $xb++){
			$nome_arq_dn = str_replace("," , "_",  $all_users[$chaves_allusers[$xb]]['dn']);
			$arquivo_csv = "csv_".date('Y-m-d_h_i_s')."_".$nome_arq_dn."_allusers.csv";
			$Handle = fopen($arquivo_csv, 'w');
			$csv_cabecalho = '"DN Grupo","SAMAccountName","Group","User","Tipo","Status","DN Objeto"'.chr(13).chr(10);
			fwrite($Handle, $csv_cabecalho); 
			
			if(isset($all_users[$chaves_allusers[$xb]]['usuarios'])){
				for($xc=0; $xc<count($all_users[$chaves_allusers[$xb]]['usuarios']); $xc++){
					if($all_users[$chaves_allusers[$xb]]['usuarios'][$xc]['DN_Objeto'] != "0"){
						$tmp1 = $all_users[$chaves_allusers[$xb]]['usuarios'][$xc];
						$cvs_lista = '"'.$tmp1['DN_Grupo'].'","'.$tmp1['SAMAccountName'].'","'.$tmp1['Group'].'","'.$tmp1['User'].'","'.$tmp1['Tipo'].'","'.$tmp1['Status'].'","'.$tmp1['DN_Objeto'].'"'.chr(13).chr(10);
						fwrite($Handle, $cvs_lista);
						unset($tmp1);
					}
				}
			}
			fclose($Handle); 
		}
	}
}

?>