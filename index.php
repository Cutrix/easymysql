<?php

require './easymysql.php';

$db = new PDO('mysql:host=localhost;dbname=epl', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$easy = new easymysql($db);
//var_dump($easy->getFromMysql('epl_user', PDO::FETCH_OBJ, 'nom', 'age'));

//var_dump($easy->updateFromMysql('epl_user', array('nom', 'prenom'), array('prince', 'boateng')));

//var_dump($easy->getFromMysql('epl_user', PDO::FETCH_OBJ, 'nom'));

//var_dump($easy->getFromMysqlOptions('epl_user', 0, array('nom'), array('baba'), 'age'));
//$easy->deleteFromMysql('epl_user', 'nom', 'baba');

echo $easy->getFromMysqlOptions('epl_user', PDO::FETCH_ASSOC, array('nom'), array('bichi'), "age")['age'];
