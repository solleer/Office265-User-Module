<?php

namespace Office365User\Model;

class Status {
    public function setOffice365Vars(array $vars) {
        $_SESSION['office365'] = $vars;
        return true;
    }

    public function getOffice365Vars() {
        return $_SESSION['office365'];
    }

    public function getAccessToken() {
        return $this->getOffice365Vars()['access_token'] ?? null;
    }
}
