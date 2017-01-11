<?php

namespace Office365User\Model;

class Status {
    public function setOffice365Vars(array $vars) {
        $_SESSION['office365'] = $vars;
        return true;
    }

    public function getSigninVars() {
        return $_SESSION['office365'];
    }
}
