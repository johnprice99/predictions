<?php

namespace EatSleepCode\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Setting {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $key;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $value;

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getKey() {
        return $this->key;
    }

    public function setKey($key) {
        $this->key = $key;
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
    }

}
