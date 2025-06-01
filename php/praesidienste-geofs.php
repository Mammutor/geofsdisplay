<?php $url =  "https://giv-geofs.uni-muenster.de/";

$input = @file_get_contents($url) or die('Could not access file: $url'); 

if( preg_match( '~(<table[^>]*id="praesenzzeiten"[^>]*>.*</table>)~si', $input, $content ) )
{ 
    echo trim( $content[1] ); 
}
?>
