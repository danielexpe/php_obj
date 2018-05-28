<?php
$count = 80;
echo "\n";
echo "Bar 1: \n";
system("tput civis      -- invisible");
system("echo -ne 
'\033[35m[.................................................] \033[m'");
$ult=0;
for( $i=0; $i<$count; $i++ )
{
         $porcentage = ($i / $count * 100);
         show_bar_porcentage($porcentage, $ult);
         $ult = $porcentage;
}
show_bar_porcentage(100, 0);

//### Functions
function show_bar_porcentage($porcentage, $ult){
    $metade = round($porcentage /2 , 0);
    if(round($porcentage, 0) != round($ult, 0)){
       $sting_p ="";
       for($z=1; $z<$metade; $z++){
          $sting_p .= "#";
       }
       system("echo -ne '\033[G'");
       system("echo -ne '\033[0C'");
       system("echo -ne '\033[32m".$sting_p."\033[m'");
       system("echo -ne '\033[52G'");
       system("echo -n ".round($porcentage, 0)."%");
    }
}
system("tput cnorm   -- normal");
echo "\n";

