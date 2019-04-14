<?php
namespace Office365User\Model\Authorize;
class User implements \User\Model\Authorizable {
    private $auth;
    private $signin;
    private $status;
    private $request;

    public function __construct(
        \Office365User\Model\Office365Auth $auth,
        \Office365User\Model\Signin $signin,
        \Office365User\Model\Status $status,
        \Level2\Core\Request $request
    ) {
        $this->auth = $auth;
        $this->signin = $signin;
        $this->status = $status;
        $this->request = $request;
    }

    public function authorize($user, array $args) {
        if (empty($user)) return false;
        if ($this->request->get('url') === 'user/signin') return true;

        $status = $this->status->getOffice365Vars();
        if (empty($status)) return false;
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

            $this->status->setOffice365Vars([
                'access_token' => $accessToken->getToken(),
                'refresh_token' => false,
                'token_expires' => $accessToken->getExpires()
            ]);
        }

        return true;
    }
}
