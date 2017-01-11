<?php
namespace Office365User\Model;
class Office365Auth {
    private $clientId;
    private $clientSecret;
    private $provider;
    private $validEmailDomains;

    public function __construct($clientId, $clientSecret, array $validEmailDomains) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->validEmailDomains = $validEmailDomains;
        $this->provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => $this->clientId,
            'clientSecret'            => $this->clientSecret,
            'redirectUri'             => 'http://localhost/helpit/user/signin',
            'urlAuthorize'            => 'https://login.microsoftonline.com/common/oauth2/authorize',
            'urlAccessToken'          => 'https://login.microsoftonline.com/common/oauth2/token',
            'urlResourceOwnerDetails' => '',
            'scopes'                  => 'openid mail.send'
        ]);
    }

    public function getProvider() {
        return $this->provider;
    }

    public function getAccessToken($code) {
        try {
            $accessToken = $this->provider->getAccessToken('authorization_code', [
                'code'     => $code,
                'resource' => 'https://graph.microsoft.com'
            ]);
            return $accessToken;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    public function getUserEmail($token) {
        $decodedAccessTokenPayload = base64_decode(
            explode('.', $token)[1]
        );
        $jsonAccessTokenPayload = json_decode($decodedAccessTokenPayload, true);

        // The following user properties are needed in the next page
        return $jsonAccessTokenPayload['unique_name'];
    }

    public function validateEmail($email): bool {
        $emailDmain = explode('@', $email)[1];
        foreach ($this->validEmailDomains as $validEmailDomain)
            if ($emailDmain === $validEmailDomain) return true;

        return false;
    }

    public function refreshToken($refreshToken) {
        try {
            $accessToken = $this->provider->getAccessToken('authorization_code', [
                'refresh_token'     => $refreshToken,
                'resource' => 'https://graph.microsoft.com',
                'grant_type' => 'refresh_token'
            ]);
            return $accessToken;
        }
        catch (\Exception $e) {
            return false;
        }
    }
}
