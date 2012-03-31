<?php 
// marczhermo@gmail.com
function curlfetch($link, $method, $values, $head=FALSE, $referer="") {
    //temp variables
    $tmp_array = array();
    $tmp_string = "";
    $ret_array = array();
    $ch = curl_init();

    if(is_array($values)) {
        foreach ($values as $key => $value) {
            if(strlen(trim($value))>0)
                $tmp_array[] = $key . "=" . urlencode($value);
            else
                $tmp_array[] = $key;
        }
        $tmp_string = join('&', $tmp_array);
    }

    if($method == "HEAD") {
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE);
    } else {
        if($method == "GET") {
            if(isset($tmp_string)) $link = $link . "?" . $tmp_string;
            curl_setopt ($ch, CURLOPT_HTTPGET, TRUE); 
            curl_setopt ($ch, CURLOPT_POST, FALSE); 
        }
        if($method == "POST") {
            if(isset($tmp_string)) curl_setopt ($ch, CURLOPT_POSTFIELDS, $tmp_string);
            curl_setopt ($ch, CURLOPT_POST, TRUE); 
            curl_setopt ($ch, CURLOPT_HTTPGET, FALSE); 
        }
        curl_setopt($ch, CURLOPT_HEADER, $head);
        curl_setopt($ch, CURLOPT_NOBODY, FALSE);
    }

    curl_setopt($ch, CURLOPT_URL, $link); 
    curl_setopt($ch, CURLOPT_REFERER, $referer); 
    curl_setopt($ch, CURLOPT_VERBOSE, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 4); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    $ret_array['data']   = curl_exec($ch); 
    $ret_array['log']  = curl_error($ch);
    curl_close($ch);

    return $ret_array;
}

$test = curlfetch("https://api.github.com", "HEAD","", TRUE);
echo '<pre>';var_dump($test);