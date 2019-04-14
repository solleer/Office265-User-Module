<?php

namespace Office365User\Model\Form;

class Signout implements \MVC\Model\Form {
    private $model;
    public $submitted = false;
    public $successful = false;

    public function __construct(\Office365User\Model\Signin $model) {
        $this->model = $model;
    }

    public function main($data) {

    }

    public function submit($data) {
        $this->submitted = true;

        return $this->model->signout();
    }

    public function success() {
        $this->successful = true;
    }
}