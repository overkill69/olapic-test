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

class InstaController {   

    protected $_storage;
    private $_credentials;
    private $_mediId;
    protected $scopes = array('basic', 'comments', 'relationships', 'likes');
    private $_currentUri;

    // Instantiate the Instagram service using the credentials, http client and storage mechanism for the token
    // instagram endpoint https://api.instagram.com/v1/media/{media-id}?access_token=ACCESS-TOKEN
    /**  */
    public function __construct($param) {
        $this->_storage = new Session();
        $this->_credentials = new Credentials(
            $param['instagram']['key'],
            $param['instagram']['secret'],
            $this->_currentUri->getAbsoluteUri()
        );
        $instagramService = $serviceFactory->createService('instagram', $credentials, $storage, $scopes);
        
        if (!empty($_GET['code'])) {
            // This was a callback request from Instagram, get the token
            $instagramService->requestAccessToken($_GET['code']);

            // Send a request with it
            $result = json_decode($instagramService->request('media/'.$mediaId), true);

            // Show some of the resultant data
            echo 'Your unique instagram user id is: ' . $result['data']['id'] . ' and your name is ' . $result['data']['full_name'];

        } elseif (!empty($_GET['go']) && $_GET['go'] === 'go') {
            $url = $instagramService->getAuthorizationUri();
            header('Location: ' . $url);
        } else {
            $url = $this->_currentUri->getRelativeUri() . '?go=go';
            echo "<a href='$url'>Login with Instagram!</a>";
        }
    }    
}
