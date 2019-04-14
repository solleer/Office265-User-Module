<?php
namespace Office365User\Model;
use League\OAuth2\Client\Provider\GenericProvider;
class Office365Auth {
    private $provider;
    private $validEmailDomains;

    public function __construct(GenericProvider $provider, array $validEmailDomains) {
        $this->validEmailDomains = $validEmailDomains;
        $this->provider = $provider;
    }

    public function getProvider() {
        return $this->provider;
    }

    public function getToken(array $properties) {
        try {
            $accessToken = $this->provider->getAccessToken('authorization_code', $properties);
            return $accessToken;
        }
        catch (\Exception $e) {
            error_log($e);
            return false;
        }
    }

    public function refreshToken($refreshToken) {
        return $this->getToken([
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token'
        ]);
    }
    
    public function getAccessToken($code) {
        try {
            $accessToken = $this->provider->getAccessToken('authorization_code', [
                'code' => $code
            ]);
            return $accessToken;
        }
        catch (\Exception $e) {
            error_log($e);
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
        $emailDomain = explode('@', $email)[1];
        foreach ($this->validEmailDomains as $validEmailDomain)
            if ($emailDomain === $validEmailDomain) return true;

        return false;
    }
}
