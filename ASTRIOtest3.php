<?php
function tagNewArray($array,$firstPosition,$tag, &$lastPosition){
    if($lastPosition !== false){
        if($lastPosition-$firstPosition != 1){
            $newArray = array();
            for ($j=$firstPosition; $j <= $lastPosition; $j++) {
                $newArray[$j] = $array[$j];
            }
            $arrayValues = array_count_values($newArray);
            if($arrayValues["</$tag>"] != $arrayValues["<$tag>"]){
                $arraySliceLastTag = array_slice($array,$lastPosition+1,count($array), TRUE);
                $lastPosition = array_search("</$tag>",$arraySliceLastTag);
                if($lastPosition !== false){
                    tagNewArray($array,$firstPosition,$tag,$lastPosition);
                }
                else $result = false;
            }
            else $result = true;
        }
        else $result = true;
    }
    else $result = false;
return $result;
}
function correctHTML($array) {
    $result = true;
    for($i=0;$i<count($array);$i++){
        $arrayValues = array_count_values($array);
        $arrSplit = str_split($array[$i]);
        $search = ["<","/",">"];
        $arrayDiff = array_diff($arrSplit,$search);
        $implodeTag = implode($arrayDiff);
        if($arrayValues["<$implodeTag>"] != $arrayValues["</$implodeTag>"]){
            if($arrayValues["<$implodeTag>"] > $arrayValues["</$implodeTag>"])
                $result = false;
            elseif($arrayValues["<$implodeTag>"] < $arrayValues["</$implodeTag>"])
                $result = false;
        }
        else {
            if($arrSplit[1] != '/'){
                if($i == count($array)+1){
                    $result = false;
                }
                else{
                    $arraySlice = array_slice($array,$i,count($array),TRUE);
                    $lastPosition = array_search("</$implodeTag>",$arraySlice);
                    $resultTagNewArray = tagNewArray($array,$i,$implodeTag,$lastPosition);
                    if($resultTagNewArray === false){
                        $result = false;
                    }
                }
            }
            elseif($arrSplit[1] == '/'){
                if($i === 0){
                    $result = false;
                }
            }
        }
    }
    if($result == false) $result = "Некорректный HTML";
    else $result = "Корректный HTML";
    return $result;
}
$array = ["<div>","<div>","<a>","</div>","<span>","<b>","</b>","<b>","</b>","</b>","<b>","</span>","</a>","</div>"];
correctHTML($array);
?>
