<?php
namespace Office365User\Model\Authorize;
class User implements \User\Model\Authorizable {
    private $auth;
    private $signin;
    private $status;

    public function __construct(\User\Model\Office365Auth $auth, \User\Model\Signin $signin, \Office365User\Model\Status $status) {
        $this->auth = $auth;
        $this->signin = $signin;
        $this->status = $status;
    }

    public function authorize($user, array $args) {
        if (empty($user)) return false;

        $status = $this->status->getOffice365Vars();
        if (time() > $status['token_expires']) {
            if (!$status['refresh_token']) {
                $this->signin->signout();
                return false;
            }

            $accessToken = $this->auth->refreshToken($_SESSION['refresh_token']);

            if (!$accessToken) {
                $this->signin->signout();
                return false;
            }

            $status = [];
            $status['access_token'] = $accessToken->getToken();
            $status['refresh_token'] = false;
            $status['token_expires'] = $accessToken->getExpires();
            $this->status->setOffice365Vars($status);
        }

        return true;
    }
}
