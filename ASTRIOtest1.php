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
function searchCategory($array,$id, &$result = 0){
	if($array['id'] == $id){
	 $result = $array['title'];
	}
	foreach ($array as $key => $value) {
		if(is_array($value) || $key == 'children'){
			searchCategory($value,$id,$result);
		}
	}
	return $result;
}
searchCategory($categories,4);
?>
