<?php

// http://www.jstott.me.uk/phpcoord/ (GPL)
require 'lib/phpcoord.php';

$input = 'data/Listed_Buidings_gml/listed_buildings.gml';
$output = fopen('data/output.tsv', 'w');

$fields = array('latitude', 'longitude', 'grade', 'name');
fputcsv($output, $fields, "\t");

$reader = new XMLReader;
$reader->open($input);

while ($reader->read()) {
  if ($reader->nodeType == XMLREADER::ELEMENT && $reader->localName == 'listed_buildings') {
    $dom = new DOMDocument;
    $dom->appendChild($dom->importNode($reader->expand(), true));

    $xml = simplexml_import_dom($dom);
    $gml = $xml->children('http://www.opengis.net/gml');
    $gml2 = $xml->children('http://www.safe.com/gml2');

    $ref = explode(',', (string) $gml->pointProperty->Point->coordinates);
    $osref = new OSRef((float) $ref[0], (float) $ref[1]);
    $latlng = $osref->toLatLng();

    $data = array(
    	'latitude' => $latlng->lat,
    	'longitude' => $latlng->lng,
    	'grade' => (string) $gml2->GRADE,
    	'name' => (string) $gml2->NAME,
    );

    fputcsv($output, $data, "\t");

    if (++$i % 10000 === 0) {
    	//print_r($data);
	    print "Parsed $i items\n";
    }
  }
}

print "Parsed $i items\n";
