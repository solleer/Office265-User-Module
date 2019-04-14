<?php
namespace Office365User\Model;
class ProviderFactory {
    private $clientId;
    private $clientSecret;
    private $redirectUriPaths;
    private $env;

    public function __construct($clientId, $clientSecret, $redirectUriPaths, \Config\Environment $env) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUriPaths = $redirectUriPaths;
        $this->env = $env;
    }

    public function createProvider() {
        return new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => $this->clientId,
            'clientSecret'            => $this->clientSecret,
            'redirectUri'             => $this->redirectUriPaths[$this->env->getIsOnline() ? 'online' : 'local'],
            'urlAuthorize'            => 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize',
            'urlAccessToken'          => 'https://login.microsoftonline.com/common/oauth2/v2.0/token',
            'urlResourceOwnerDetails' => '',
            'scopes'                  => 'openid '
        ]);
    }
}
