<?php

namespace EatSleepCode\APIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class League {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="owner", referencedColumnName="id")
     **/
    protected $owner;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $code;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="leagues")
     * @ORM\JoinTable(name="league_entrants",
     *     joinColumns={@ORM\JoinColumn(name="league_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     **/
    protected $entrants;

    public function __construct() {
        $this->entrants = new ArrayCollection();
        $this->code = strtoupper(substr(str_shuffle(MD5(microtime())), 0, 10));
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setOwner(\EatSleepCode\APIBundle\Entity\User $owner = null) {
        $this->owner = $owner;
        $this->addEntrant($owner);
    }

    public function getOwner() {
        return $this->owner;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function getCode() {
        return $this->code;
    }

    public function addEntrant(\EatSleepCode\APIBundle\Entity\User $entrant = null) {
        $this->entrants->add($entrant);
    }

    public function hasEntrant(\EatSleepCode\APIBundle\Entity\User $entrant = null) {
        return $this->entrants->contains($entrant);
    }

    public function removeEntrant(\EatSleepCode\APIBundle\Entity\User $entrant = null) {
        $this->entrants->removeElement($entrant);
    }

    public function jsonEncode() {
        return array(
            'id' => $this->getId(),
            'ownerId' => $this->getOwner()->getId(),
            'name' => $this->getName(),
            'code' => $this->getCode(),
        );
    }

}
