<?php

//require __DIR__ . "../vendor/autoload.php";
require "../vendor/autoload.php";

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;

set_exception_handler("ErrorHandler::handleException");

$dotenv =  Dotenv\Dotenv::createImmutable( dirname(__DIR__));
$dotenv->load();

$total_page = 4;

$client = new Client();

$requests = function ($total) {
    for ($i = 2; $i <= $total; $i++) {
       // echo $i;
        $uri = 'https://trial.craig.mtcserver15.com/api/properties?page%5Bnumber%5D=' . $i;
        yield new Request('GET', $uri);
    }
};

$pool = new Pool($client, $requests(10), [
    'concurrency' => 200,
    'fulfilled' => function ($response, $index) {

        //  echo substr($response->getBody(), 0, 200), "...\n";
    },
    'rejected' => function ($reason, $index) {
        echo 'sorry' . "\n";
    },
]);

$promise = $pool->promise();

$promise->wait();

$pool_batch = Pool::batch($client, $requests(10));

foreach ($pool_batch as $pool => $res) {
    if ($res instanceof RequestException) {
        echo 'sorry';
        continue;
    }

   // echo substr($res->getBody(), 0, 200), "...\n";
    $data = json_decode($res->getBody()->getContents(), true);
}
$database = new Database($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASS"]);

$task_gateway = new TaskGateway($database);

$controller = new TaskController($task_gateway);

$controller->processRequest($data);
