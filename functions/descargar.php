<?php 

include("variables.php");

$DB=new DataAccess();

$DB->query('select max(id),xml from xml_caixa');

$xml=$DB->fetch();

header("Content-Type: application/xml");

header("Expires: 0");

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

header("content-disposition: attachment;filename=xml_caixa.xml");

echo $xml['xml'];


 ?>