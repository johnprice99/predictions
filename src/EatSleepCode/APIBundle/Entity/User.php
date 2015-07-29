<?php

namespace EatSleepCode\APIBundle\Entity;

use EatSleepCode\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_account")
 */
class User extends BaseUser {

//    /**
//     * @ORM\OneToMany(targetEntity="Idea", mappedBy="user")
//     */
//    protected $predictions;

    public function __construct() {
        parent::__construct();
//        $this->roles = array('ROLE_ADMIN');
//        $this->predictions = new ArrayCollection();
    }

//    public function getPredictions() {
//        return $this->predictions;
//    }
//
//    public function setPredictions($predictions) {
//        $this->predictions = $predictions;
//    }
//
}
