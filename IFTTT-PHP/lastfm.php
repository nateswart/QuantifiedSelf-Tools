#!/usr/bin/php
<?php
	
function processLastfmContents(&$contents) {

  // Get more details from Last.fm
  $trackName = urlencode($contents->{'trackName'});
  $artist = urlencode($contents->{'artist'});
  $detailsUrl = "http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=" . LASTFM_APIKEY 
    . "&artist=" . $artist 
    . "&track=" . $trackName 
    . "&format=json"
    . "&autocorrect=1" ;
  
  $result = file_get_contents($detailsUrl); 
  $details = json_decode($result, true);
  
  // Musicbrainz id
  if (isset($details['track']['mbid'])) {
    $contents->{'mbid'} = $details['track']['mbid']; 
  } 
  // Duration
  $contents->{'duration'} = $details['track']['duration'];

  // Lastfm autocorrect is turned on
  // Re-set these fields with WS data to ensure they are correct

  // Artist
  $contents->{'artist'} = $details['track']['artist']['name'];

  // Track name
  $contents->{'trackName'} = $details['track']['name'];

  // Album name
  $contents->{'albumName'} = $details['track']['album']['title'];

  // Genre tags
  if (isset($details['track']['toptags']['tag'])) {
    $genres = Array();
    foreach($details['track']['toptags']['tag'] as $tag) {
      $genres[] = $tag['name'];
    }
    $contents->{'genres'} = $genres;
  }
}