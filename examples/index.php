<?php

require_once('../src/Druckerwolke.class.php');

$username = 'XXX';
$password = 'XXXXXXXXXX';
$api_key = 'XXXXXXXX-XXXX-MXXX-NXXX-XXXXXXXXXXXX';

$druckerwolke = Druckerwolke($username, $password, $api_key);

$printers = $druckerwolke->printers();

//	SELECT THE PRINTER

$printer_id = $printers[0]->Id; //we are using the first printer

//	UPLOAD A FILE USING $file_content OR $file_url

$pdfName = 'document.pdf';
$file_content = file_get_contents('path/to/document.pdf');
$file_url = 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';

$data = [
	'FileName' => $pdfName,
	'MimeType' => 'application/pdf',
//	'FileDataUri' => $file_url, 
	'FileDataBase64' => base64_encode($file_content),
	'JobName' => 'Printing: '.$pdfName,
	'DocumentVersion' => 0,
	'InputQueueId' => $printer_id,
	'FileSize' => 0,
	'JobSettings' => [
		'PageOrientation' => 0
	],
	'AdditionalParameters' => []
];

$result = $druckerwolke->add_document($data);

print_r($result);

?>