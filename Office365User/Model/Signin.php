<?php
namespace User\Model;
class Signin {
    private $model;
    private $users;
    private $userStatus;
    private $office365Status;

    public function __construct(Office365Auth $model, \Maphper\Maphper $users,
                                \User\Model\Status $userStatus, \Office365User\Model\Status $office365Status) {
        $this->model = $model;
        $this->users = $users;
        $this->userStatus = $userStatus;
        $this->office365Status = $office365Status;
    }

    public function signin($code) {
        $accessToken = $this->model->getAccessToken($code);
        $email = $this->model->getUserEmail($accessToken->getToken());
        if (explode('@', $email)[1] !== 'bolles.org') return false;
        if (!empty($this->users[$email])) {
            $status = [];
            $status['access_token'] = $accessToken->getToken();
            $status['refresh_token'] = $accessToken->getRefreshToken();
            $status['token_expires'] = $accessToken->getExpires();
            $this->office365Status->setOffice365Vars($status);
            $this->userStatus->setSigninVar($email);
        }
        else return false;
    }

    public function getProvider() {
        return $this->model->getProvider();
    }

    public function signout() {
        $this->office365Status->setOffice365Vars(null);
        $this->userStatus->setSigninVar(null);
        return true;
    }
}
