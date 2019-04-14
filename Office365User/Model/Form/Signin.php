<?php

namespace User\Model\Form;

class Signin implements \MVC\Model\Form {
    private $model;
    private $request;
    private $userStatus;
    public $submitted = false;
    public $successful = false;

    public function __construct(\Office365User\Model\Signin $model, \Level2\Core\Request $request,
                                \Solleer\User\SigninStatus $userStatus) {
        $this->model = $model;
        $this->request = $request;
        $this->userStatus = $userStatus;
    }

    public function main($data) {

    }

    public function submit($data) {
        $this->submitted = true;

        if ($this->request->get('code')) {
            if ($this->userStatus->getSigninID() === null && !$this->model->signin($this->request->get('code'))) return false;
            return true;
        }
    }

    public function success() {
        $this->successful = true;
    }

    public function getProvider() {
        return $this->model->getProvider();
    }
}