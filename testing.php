<?php

require __DIR__.'/vendor/autoload.php';

$client = new \GuzzleHttp\Client([
   'base_uri' => 'http://localhost:8000',
   'defaults' => [
        'exceptions' => false,
    ]
]);

$data = array(
   'title' => 'first list test',
    'description' => 'list1 list1',
    'created_at' => '2018-01-02 13:15:56'
);

$response = $client->request('POST','/api/list/create',[
    'body' => json_encode($data)
]);

echo $response->getBody();
echo "\n\n";