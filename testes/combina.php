<?php
$numeros[] = 0;
$numeros[] = 1;
$numeros[] = 2;
//$numeros[] = 3;
//$numeros[] = 4;
//$numeros[] = 5;

function combina ($numeros, $num_utilizados){
   for($i=0; $i<count($numeros); $i++){
      if( !in_array($numeros[$i], $num_utilizados)){
         $num_utilizados[$i] = $numeros[$i];
         for($a=0; $a<3; $a++){
            echo $numeros[$i];
            $c = combina($numeros, $num_utilizados);
            echo " \n";
         }
      }
   }
}

$num_utilizados = array();
$combinacao = combina($numeros, $num_utilizados);
?>
