<?php
/**
 * Description of InstaController
 *
 * @author overkill
 */

namespace dataSource\Instagram;

use OAuth\OAuth2\Service\Instagram;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;

class InstaController implements \OAuth{   

    protected $_storage;
    private $_credentials;
    private $_mediaId;
    protected $scopes = array('basic', 'comments', 'relationships', 'likes');
    protected $serviceFactory;
    protected $uriFactory;
    private $_currentUri;
    private $_instagramService;

    // Instantiate the Instagram service using the credentials, http client and storage mechanism for the token
    // instagram endpoint https://api.instagram.com/v1/media/{media-id}?access_token=ACCESS-TOKEN
    /**  */
    public function __construct() {
        echo 'FUCK YEAH!!!';
        die();
        $this->serviceFactory = new \OAuth\ServiceFactory();
        $this->uriFactory = new \OAuth\Common\Http\Uri\UriFactory();
        $this->_currentUri = $uriFactory->createFromSuperGlobalArray($_SERVER);
        $this->_currentUri->setQuery('');
        $this->_storage = new Session();        
        $this->_credentials = new Credentials(
            $param['key'],
            $param['secret'],
            $this->_currentUri->getAbsoluteUri()
        );
        $this->_instagramService = $this->serviceFactory->createService('instagram', $this->_credentials, $this->_storage, $this->scopes);
        $this->_instagramService->requestAccessToken($_GET['code']);
        $result = json_decode($this->_instagramService->request('users/self'), true);
        var_dump($result);
        return TRUE;
    }
    
    public function getData($param) {
        
        
        
        if (!empty($_GET['code'])) {
            $instagramService->requestAccessToken($_GET['code']);
            $result = json_decode($instagramService->request('users/self'), true);
            var_dump($result);

        } elseif (!empty($_GET['go']) && $_GET['go'] === 'go') {
            $url = $instagramService->getAuthorizationUri();
            header('Location: ' . $url);
        } else {
            $url = $this->_currentUri->getRelativeUri() . '?go=go';
            echo "<a href='$url'>Login with Instagram!</a>";
        }
    }    
}
