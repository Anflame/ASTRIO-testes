<?php
function correctHTML($array){
    $b = 0;
    for($i=0;$i<count($array);$i++){
        $a = str_split($array[$i]);
        $seach = array("<","/",">");
        if($a[1] != '/'){
            $a = array_diff($a,$seach);
            $a = implode($a);
            if(!in_array("</$a>",$array)){
                $b++;
            }
        }
        elseif($a[1] == '/'){
            $a = array_diff($a,$seach);
            $a = implode($a);
            if(!in_array("<$a>",$array)){
                $b++;
            } 
        }
    }
    if($b == 0){
        echo "Корректный код HTML";
    }
    else  echo "Не корректный код HTML";
}
$array = array("<a>","<div>","</div>","</a>","<b>","</b>");
correctHTML($array);
?>