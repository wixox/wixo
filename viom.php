<?php 
$code = GET['co'];
$link = base64_decode($link);
$url  = 'https://vidbam.org/'.$link;
function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
$line = get_data($url);
$vsource =  preg_replace('/<head\b[^>]*>(.*?)<\/head>/is', "", $line);
$view =  preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $vsource , 3);

$dom = new DOMDocument();
@$dom->loadHTML($view);

$xpath = new DOMXPath($dom);


foreach ($xpath->query("//script[@type=\"text/javascript\"]") as $script) {
    if (stristr($script->nodeValue, 'sources') !== false) {
        //preg_match_all('/{\s*file\s*:\s*["\']\s*(http[^"\']+\.mp4)/i', $script->nodeValue, $match);
        //$sources = $match[0];
        
        preg_match('/{\s*file\s*:\s*["\']\s*(http[^"\']+\.mp4)/i', $script->nodeValue, $match);
        $sources = $match[0];
    }
}
$vidbam = substr($sources, 7);
echo base64_encode($vidbam);
?>