<?php
namespace Office365User\Model;
class Signin {
    private $model;
    private $users;
    private $userStatus;
    private $office365Status;

    public function __construct(Office365Auth $model, \Solleer\User\User $users,
                                \Solleer\User\SigninStatus $userStatus, \Office365User\Model\Status $office365Status) {
        $this->model = $model;
        $this->users = $users;
        $this->userStatus = $userStatus;
        $this->office365Status = $office365Status;
    }

    public function signin($code) {
        $accessToken = $this->model->getAccessToken($code);
        if ($accessToken === false) return false;
        $email = strtolower($this->model->getUserEmail($accessToken->getToken()));
        if ($this->users->getUser($email)) {
            $this->office365Status->setOffice365Vars([
                'access_token' => $accessToken->getToken(),
                'refresh_token' => $accessToken->getRefreshToken(),
                'token_expires' => $accessToken->getExpires()
            ]);
            $this->userStatus->setSigninID($email);
            return true;
        }
        else return false;
    }

    public function getProvider() {
        return $this->model->getProvider();
    }

    public function signout() {
        $this->office365Status->setOffice365Vars([]);
        $this->userStatus->setSigninID(null);
        return true;
    }
}
