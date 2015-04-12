# IFTTT-PHP

This code is designed to process files created by IFTTT (following the QS Data Spec found here: https://github.com/nateswart/QuantifiedSelf-Data-Spec). 


Note: this is a work in progress. Once it is more complete, this README will be updated with links to published IFTTT recipes that will work with this code.

**Example:**

Create an IFTTT recipe to write Last.fm records to Dropbox in the following format:

    {<br>
    "dataType": "audioscrobble",<br>
    "dataSource": "Lastfm",<br>
    "timestamp": "{{PlayedDate}}",<br>
    "artist": "{{Artist}}",<br>
    "trackName": "{{TrackName}}",<br>
    "albumName": "{{AlbumName}}",<br>
    "trackUrl": "{{TrackUrl}}"<br>
    }<br>

You can then process this file with this set of scripts. To run:

`php ifttt.php <filename>`


