<?php

namespace EatSleepCode\APIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Prediction {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     **/
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Fixture")
     * @ORM\JoinColumn(name="fixture", referencedColumnName="id")
     **/
    protected $fixture;

    /**
     * @ORM\Column(type="integer")
     */
    protected $homeScore;

    /**
     * @ORM\Column(type="integer")
     */
    protected $awayScore;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $points;

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setHomeScore($homeScore) {
        $this->homeScore = $homeScore;
    }

    public function getHomeScore() {
        return $this->homeScore;
    }

    public function setAwayScore($awayScore) {
        $this->awayScore = $awayScore;
    }

    public function getAwayScore() {
        return $this->awayScore;
    }

    public function setUser(\EatSleepCode\APIBundle\Entity\User $user = null) {
        $this->user = $user;
    }

    public function getUser() {
        return $this->user;
    }

    public function setFixture(\EatSleepCode\APIBundle\Entity\Fixture $fixture = null) {
        $this->fixture = $fixture;
    }

    public function getFixture() {
        return $this->fixture;
    }

    public function setPoints($points) {
        $this->points = $points;
    }

    public function getPoints() {
        return $this->points;
    }

}
