<?php
$m = array('','','','','','','','','');
$value="X";
$value2p="O";
echo "\nChoice a place in the array [0-8]: ";
fscanf(STDIN, "%d\n", $number); // reads number from STDIN
$m[$number] = $value;

check_m($m);
$ppp = run_turn($m, $value, $value2p);
$m[$ppp[0]] = $value2p;
print_r($m);

while($ppp[0] != -1){
   echo "\nChoice a place in the array [0-8]: ";
   fscanf(STDIN, "%d\n", $number); // reads number from STDIN
   if($m[$number] == ""){
	   $m[$number] = $value;
   }
   else
   {
      echo "\nError! The place ".$number." is not empty. Tried by".$value."\n";
      exit;
   }
   check_m($m);
   $ppp = run_turn($m, $value, $value2p);
   print_r($m);
   check_if_all_positions_are_busy($m);

   if($m[$ppp[0]] == ""){
	   $m[$ppp[0]] = $value2p;
   }
   else
   {
      echo "\nError! The place ".$number." is not empty. Tried by".$value2p."\n";
   }
   check_m($m);
   $ppp = run_turn($m, $value, $value2p);
   print_r($m);
   check_if_all_positions_are_busy($m);
}
exit;

//### Functions ###
function check_if_all_positions_are_busy($m){
   //Check if all positions are already busy
   $ok = "no";
   for($yy=0; $yy<count($m); $yy++){
      if($yy != 9){
         if($m[$yy] == ''){
              $ok = "ok";
         }
      }
   }
   if($ok == "no"){
      $ppp[0] = -1;
      echo "It's Full!\n";
      exit;
   }

}
function run_turn($m, $value, $value2p){
   // Check by lines
   $check[0] = check($value, $value2p, $m[0], $m[1], $m[2]); //h1
   $check[1] = check($value, $value2p, $m[3], $m[4], $m[5]); //h2
   $check[2] = check($value, $value2p, $m[6], $m[7], $m[8]); //h3
   $check[3] = check($value, $value2p, $m[0], $m[3], $m[6]); //v1
   $check[4] = check($value, $value2p, $m[1], $m[4], $m[7]); //v2
   $check[5] = check($value, $value2p, $m[2], $m[5], $m[8]); //v3
   $check[6] = check($value, $value2p, $m[0], $m[4], $m[8]); //d1
   $check[7] = check($value, $value2p, $m[2], $m[4], $m[6]); //d2

   // Organize by priority
   for($i=0; $i<count($check); $i++){
      $severity = $check[$i]['severity'];
      $priority[$severity][$i]= $check[$i]['pos'];
   }

   // Get just the Highest priority group
   if(isset($priority[0])){
      $pp = $priority[0];
   } else if(isset($priority[3])){
      $pp = $priority[3];
   } else if(isset($priority[2])){
      $pp = $priority[2];
   } else if(isset($priority[1])){
      $pp = $priority[1];
   } else {
      echo "\n Error! The var \$pp is not an array! \n";
      exit(2);
   }

   // Load dictionary to translation in the var
   $translate = translate();

   // Translate from line/cel mode to absolute mode
   foreach ($pp as $key1 => $value1) {
      foreach ($value1 as $value2) {
         if(isset($translate[$key1][$value2])){
            $ppp[] = $translate[$key1][$value2];
         }
         else
         {
            $ppp[] = -1;
         }
      }
   }
   // Sort the array in randomic order
   shuffle($ppp);
   return $ppp;
}

function check($value, $value2p, $pos1, $pos2, $pos3){
   global $m;
   $r = array();
   $r['pos']=array(1, 2, 3);
   $r['severity']=1;
   if( ($value == $pos1) && ($value == $pos2) && ($value == $pos3)) {
        $r['pos']=array(0);	
        $r['severity']=0;
	print_r($m);
	echo "\n The ".$value." is the guy!\n";
	exit;
   }else if( ($value2p == $pos1) && ($value2p == $pos2) && ($value2p == $pos3)) {
        $r['pos']=array(0);
        $r['severity']=0;
	print_r($m);
	echo "\n The ".$value2p." is the guy!\n";
	exit;
   }else if( (($value == $pos1) && ($value == $pos2) && ($pos3 == '')) || (($value2p == $pos1) && ($value2p == $pos2) && ($pos3 == '')) ){
        $r['pos']=array(3);	
        $r['severity']=3;
   }else if( (($value == $pos2) && ($value == $pos3) && ($pos1 == '')) || (($value2p == $pos2) && ($value2p == $pos3) && ($pos1 == '')) ){
        $r['pos']=array(1);	
        $r['severity']=3;
   }else if( (($value == $pos1) && ($value == $pos3) && ($pos2 == '')) || (($value2p == $pos1) && ($value2p == $pos3) && ($pos2 == '')) ){
        $r['pos']=array(2);	
        $r['severity']=3;
   }else if( (($value == $pos1) && ($pos2 == '') && ($pos3 == '')) || (($value2p == $pos1) && ($pos2 == '') && ($pos3 == '')) ){
        $r['pos']=array(2, 3);
        $r['severity']=2;
   }else if( (($value == $pos2) && ($pos1 == '') && ($pos3 == '')) || (($value2p == $pos2) && ($pos1 == '') && ($pos3 == '')) ){
        $r['pos']=array(1, 3);
        $r['severity']=2;
   }else if( (($value == $pos3) && ($pos1 == '') && ($pos2 == '')) || (($value2p == $pos3) && ($pos1 == '') && ($pos2 == '')) ){
        $r['pos']=array(1, 2);
        $r['severity']=2;
   }
   return $r;
}

function translate(){
   //h
   $translate[0][1] = 0;
   $translate[0][2] = 1;
   $translate[0][3] = 2;
   $translate[1][1] = 3;
   $translate[1][2] = 4;
   $translate[1][3] = 5;
   $translate[2][1] = 6;
   $translate[2][2] = 7;
   $translate[2][3] = 8;
   //v
   $translate[3][1] = 0;
   $translate[3][2] = 3;
   $translate[3][3] = 6;
   $translate[4][1] = 1;
   $translate[4][2] = 4;
   $translate[4][3] = 7;
   $translate[5][1] = 2;
   $translate[5][2] = 5;
   $translate[5][3] = 8;
   //d
   $translate[6][1] = 0;
   $translate[6][2] = 4;
   $translate[6][3] = 8;
   $translate[7][1] = 2;
   $translate[7][2] = 4;
   $translate[7][3] = 6;
   return $translate;
}

function check_m($m){
   if(!is_array($m)){
      echo "\n Error! The var \$m is not an array! \n";
      exit(2);
   }
   if(count($m) != 9){
      echo "\n Error! The array \$m is not ok! \n";
      exit(2);
   }
}
?>
