<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use InstaTest\InstaController;

$app = new Application();
$app['debug'] = true;
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'dbname'   => 'test',
        'host'     => 'localhost',
        'user'     => 'root',
        'password' => '',
    ),
));
/* DB test for Silex fw
$app->get('/media/{id}', function (Silex\Application $app, $id) use ($app) {
    $sql = "SELECT *  FROM media WHERE id = ?";
    $media = $app['db']->fetchAssoc($sql, array((int) $id));
    if(!isset($media['id'])){
        $app->abort(404, "Madia $id does not exist.");
    }
    $output = '';    
    $output .= 'STATUS '.http_response_code();
    $om = array('id'=>$media['id'],'location'=>array('geopoint'=> array('latitude'=>$media['latitude'], 'longitude' =>$media['longitude'])));        
    
    echo $output;
    return $app->json($om);//$output;
});*/

$app->get('/media/{id}',function() use ($app){

});

$app->run();