<?php

namespace Office365User\Model;

class Office365User implements User\Model\User {
    private $maphper;
    private $validator;
    private $defaultAttributes = ['user_id', 'name'];
    private $userAttributes;

    public function __construct(\Maphper\Maphper $maphper, \Respect\Validation\Rules\AllOf $validator, Security $security, $additionalUserAttributes = []) {
        $this->maphper = $maphper;
        $this->validator = $validator;
        $this->userAttributes = array_merge($this->defaultAttributes, $additionalUserAttributes);
    }

    public function save(array $data, $id = null) {
        $data = $this->removeExcessAttributes($data);
        if (!$this->validator->validate((array) $data)) return false;
        $this->maphper[$id] = $data;
        return $data;
    }

    private function removeExcessAttributes($data) {
        return (object) array_filter((array)$data, function ($key) {
            return in_array($key, $this->defaultAttributes);
        }, ARRAY_FILTER_USE_KEY);
    }

    public function getUser($selector) {
        return $this->maphper[$selector] ?? false;
    }
}
