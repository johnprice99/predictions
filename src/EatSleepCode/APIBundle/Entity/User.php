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

    /**
     * @ORM\ManyToMany(targetEntity="League", mappedBy="entrants")
     */
    protected $leagues;

    public function __construct() {
        parent::__construct();
        $this->leagues = new ArrayCollection();
    }

    public function setLeagues($leagues) {
        $this->leagues = $leagues;
    }

    public function getLeagues() {
        return $this->leagues;
    }

}
