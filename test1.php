<?php
/**
 * Created by coder meng.
 * User: coder meng
 * Date: 2016/12/8 17:47
 */
$ch=curl_init('http://maps.googleapis.com/maps/api/geocode/json?address=420%209th%20Avenur%2C%20New%20York%2C%20NY%2010001%20USA');
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
//curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:64386');
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,3);
curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch,CURLOPT_MAXREDIRS,3);
$html=curl_exec($ch);
curl_close($ch);

var_dump($html);