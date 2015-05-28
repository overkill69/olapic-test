<?php
error_reporting( 'E_ALL' );
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/PHPoAuthLib/src/OAuth/Common/AutoLoader.php';

use Silex\Application;
use OAuth\OAuth2\Service\Instagram;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;
//use \dataSouce\InstaTeast\InstaController;

$app = new Silex\Application();
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app['debug'] = true;
/* DB test for Silex fw
 * $app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'dbname'   => 'test',
        'host'     => 'localhost',
        'user'     => 'root',
        'password' => '',
    ),
)); 
$app->get('/media/{id}', function (Silex\Application $app, $id) use ($app) {
    $sql = "SELECT *  FROM media WHERE id = ?";
    $media = $app['db']->fetchAssoc($sql, array((int) $id));
    if(!isset($media['id'])){
        $app->abort(404, "Media $id does not exist.");
    }
    $output = '';    
    $output .= 'STATUS '.http_response_code();
    $om = array('id'=>$media['id'],'location'=>array('geopoint'=> array('latitude'=>$media['latitude'], 'longitude' =>$media['longitude'])));        
    
    echo $output;
    return $app->json($om);//$output;
});*/
$app['instagram'] = $app->share(function() {
    $params["key"] = 'af167bb07528423b9e0801b205db4dd0';
    $params["secret"] = '124dfae2b2724d3aa9b71497a4a3a6fb';             
    $scopes = array('basic', 'comments', 'relationships', 'likes'); 
    
    $autoloader = new OAuth\Common\AutoLoader('OAuth', dirname(__DIR__).'\vendor\PHPoAuthLib\src');    
    $autoloader->register();        
    $serviceFactory = new \OAuth\ServiceFactory();    
    $uriFactory = new OAuth\Common\Http\Uri\UriFactory();    
    $currentUri = $uriFactory->createFromSuperGlobalArray($_SERVER);
    $currentUri->setQuery('');
    $storage = new Session();        
    $credentials = new Credentials(
        $params['key'],
        $params['secret'],
        'http://www.borisdixit.com/insta/'//$currentUri->getAbsoluteUri()
    );
    
    $instagramService = $serviceFactory->createService('instagram', $credentials, $storage, $scopes);
    
    $instagramService->requestAccessToken('d6792fd384544dbd8e543c09dee4e0f9');
    //die();
    $result = json_decode($instagramService->request('users/self'), true);
    /*$url = $instagramService->getAuthorizationUri();
    header('Location: ' . $url);*/
    var_dump($result);
    /*if (!empty($_GET['code'])) {
        // This was a callback request from Instagram, get the token
        $instagramService->requestAccessToken($_GET['code']);

        // Send a request with it
        $result = json_decode($instagramService->request('users/self'), true);

        // Show some of the resultant data
        echo 'Your unique instagram user id is: ' . $result['data']['id'] . ' and your name is ' . $result['data']['full_name'];

    } elseif (!empty($_GET['go']) && $_GET['go'] === 'go') {
        $url = $instagramService->getAuthorizationUri();
         echo $url;
         $app->redirect( $url);
       
    } else {
        $url = $currentUri->getRelativeUri() . '?go=go';
        echo "<a href='$url'>Login with Instagram!</a>";
    }/**/
    return TRUE;
});

function generate_sig($endpoint, $params, $secret) {
  $sig = $endpoint;
  ksort($params);
  foreach ($params as $key => $val) {
    $sig .= "|$key=$val";
  }
  return hash_hmac('sha256', $sig, $secret, false);
}



$app->get('/media/{id}',function($id) use ($app){    
    //$params["media"] = $id;    
    $endpoint = '/media/'.$id;
    $params = array(
      'access_token' => 'fc555cf71c3749a189851696b9e91140',
      'count' => 10,
    );
    $secret = '124dfae2b2724d3aa9b71497a4a3a6fb';

    $sig = generate_sig($endpoint, $params, $secret);
   // echo $sig;
    $inst = $app['instagram'];
    //var_dump($inst);
});

$app->run();