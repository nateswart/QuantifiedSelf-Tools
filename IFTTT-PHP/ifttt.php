<?php

require_once(dirname(__FILE__)."/../php_utils.php");

require_once(dirname(__FILE__)."/settings.php");
require_once(dirname(__FILE__)."/lastfm.php");


// Check to make sure at least one file has been specified to process
if (count($argv) <= 1) {
  exit ("You must specify at least one file to process\n");
}


// Loop through each element in the $argv array
for($i=1; $i<count($argv); $i++) {

  processIftttFile($argv[$i]);

}



function processIftttFile($filename) {

  // Log that we're starting with this file
  file_put_contents(LOGFILE, "Processing: " . $filename . "\n", FILE_APPEND | LOCKEX);

  // Get the JSON as a PHP object
  $contents = file_get_contents($filename);
  $iftttData = json_decode($contents);
  
  // Create a UUID
  $iftttData->{'uuid'} = generateUuid();

  // Convert date/time string to a unix timestamp
  $timestamp = convertIftttTimestamp($iftttData->{'timestamp'});
  $iftttData->{'timestamp'} = $timestamp;

  // Process the rest of the contents based on type
  switch ($iftttData->{'dataType'}) {
  	case 'audioscrobble' :
  		processLastfmContents($iftttData);
  		break;
  }

  // Rewrite the file with the updated JSON
  file_put_contents($filename, json_encode($iftttData));

  $ts = date('YmdHi', $timestamp);
  $cmd = "touch -t " . $ts . " " . $filename;
  file_put_contents(LOGFILE, $cmd . "\n", FILE_APPEND | LOCKEX);
  shell_exec($cmd);

  // Log that we've finished
  file_put_contents(LOGFILE, "Finished: " . $filename . "\n\n", FILE_APPEND | LOCKEX);
}


function convertIftttTimestamp ($iftttDateTime) {
  $iftttDateTime = str_replace('at ', '', $iftttDateTime);
  $dateTime = new DateTime($iftttDateTime, new DateTimeZone(date_default_timezone_get()));
  
  return $dateTime->format('U');
}

