<?php
/**
 * Created by coder meng.
 * User: coder meng
 * Date: 2016/12/2 10:00
 */
require '../../vendor/autoload.php';
$client= new \GuzzleHttp\Client();
$csv=\League\Csv\Reader::createFromPath('./urls.csv');
foreach($csv as $csvRow){
    try{
        $httpResponse=$client->options($csvRow[0]);
        if($httpResponse->getStatusCode()>=400){
            throw new \Exception();
        }
    }catch(\Exception $e){
        echo $csvRow[0].PHP_EOL;
    }
}