<?php

namespace Office365User\Model\Form;

class Signup implements \MVC\Model\Form {
    private $model;
    private $auth;
    public $successful = false;
    public $submitted = false;

    public function __construct(\User\Model\User $model, Office365User\Model\Office365Auth $auth) {
        $this->model = $model;
        $this->auth = $auth;
    }

    public function main($data) {
        $this->submitted = false;
        return true;
    }

    public function submit($data) {
        $this->submitted = true;
        if (!$this->auth->validateEmail($data['user_id'])) return false;
        return $this->model->save($data);
    }

    public function success() {
        $this->successful = true;
    }
}
