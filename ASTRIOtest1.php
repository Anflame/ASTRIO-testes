<?php
$categories = array(
	array(
   	"id" => 1,
   	"title" =>  "Обувь",
   	'children' => array(
       	array(
           	'id' => 2,
           	'title' => 'Ботинки',
           	'children' => array(
               	array('id' => 3, 'title' => 'Кожа'),
               	array('id' => 4, 'title' => 'Текстиль'),
           	),
       	),
       	array('id' => 5, 'title' => 'Кроссовки',),
   	)
	),
	array(
   	"id" => 6,
   	"title" =>  "Спорт",
   	'children' => array(
       	array(
           	'id' => 7,
           	'title' => 'Мячи'
       	)
   	)
	),
);
function seachCategory($arr,$id){
   foreach ($arr as $item => $value) {
    if ($item == 'children') {
        if($value['id'] == $id){
            $result = $value['title'];
            echo $result;
        }
        seachCategory($value,$id);
    }
   }
   return $result;
}
seachCategory($categories,2);
?>