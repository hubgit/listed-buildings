<?php

chdir('data');

// http://www.geostore.com/environment-agency/WebStore?xml=environment-agency/xml/ogcDataDownload.xml (Open Government Licence)
$cmd = 'wget --continue "http://www.geostore.com/inspiredata/ea_inspire_gml_zipped/Listed_Buidings.zip" --output-document="listed_buildings.zip"';
exec($cmd);

$zip = new ZipArchive;
$zip->open('listed_buildings.zip');
$zip->extractTo(__DIR__, 'Listed_Buidings_gml/listed_buildings.gml');
$zip->close();

chdir(__DIR__);
