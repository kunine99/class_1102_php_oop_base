<?php
include_once "test.php";

// $XXX->save($_POST);
// $aaa=$Students->find(1);
$aaa=$Students->all([`major` => '商業經營科']);
// SELECT * FROM `students` WHERE `major` LIKE '商業經營科'


// echo $aaa;
print_r($aaa);


