<?php
error_reporting( 'E_ALL' );
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/PHPoAuthLib/src/OAuth/Common/AutoLoader.php';

use Silex\Application;
use OAuth\OAuth2\Service\Instagram;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;

$app = new Silex\Application();
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app['debug'] = true;
$_SESSION['EndUri'] = '';

$app['instagram'] = $app->share(function($endpointUri) {
    
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
        'http://www.borisdixit.com/insta/?tar='. preg_replace('/\/media\//', '', $_SESSION['EndUri'])//$currentUri->getAbsoluteUri()
    );    
    $instagramService = $serviceFactory->createService('instagram', $credentials, $storage, $scopes);
    $output = true;
    if (!empty($_GET['code'])) {
        $instagramService->requestAccessToken($_GET['code']);
        $output = json_decode($instagramService->request($_SESSION['EndUri']), true);
        //var_dump($output);
        return $output;

    } elseif (!empty($_GET['go']) && $_GET['go'] === 'go') {
        $url = $instagramService->getAuthorizationUri();
        header('Location:'. $url);
        $code = file_get_contents($url, false, NULL);
        var_dump($code);
    } else {
        $url = $currentUri->getRelativeUri() . '?go=go';
        header('Location:'. $url);
    }
    return $output;
});

$app->get('/media/{id}',function($id) use ($app){    
        $_SESSION['EndUri'] = '/media/'.$id;
    $inst = $app['instagram'];    
    if ($inst !== TRUE ) {
        return $app->json($inst);
    }  elseif (empty($_GET['go'])) {        
        http_redirect($url, array("go" => "go"), true, HTTP_REDIRECT_PERM);
        return false;
    }
});
$app->run();