<?php

namespace EatSleepCode\APIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="EatSleepCode\APIBundle\Repository\Fixture")
 */
class Fixture {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $url;

    /**
     * @ORM\Column(type="integer")
     */
    protected $matchDay;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="home_team", referencedColumnName="id")
     **/
    protected $homeTeam;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="away_team", referencedColumnName="id")
     **/
    protected $awayTeam;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $played = false;

    /**
     * @ORM\Column(type="integer")
     */
    protected $homeScore;

    /**
     * @ORM\Column(type="integer")
     */
    protected $awayScore;

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setMatchDay($matchDay) {
        $this->matchDay = $matchDay;
    }

    public function getMatchDay() {
        return $this->matchDay;
    }

    public function setDate($dateString) {
        $this->date = new \DateTime($dateString);
    }

    public function getDate() {
        return $this->date;
    }

    public function setHomeTeam($homeTeam) {
        $this->homeTeam = $homeTeam;
    }

    public function getHomeTeam() {
        return $this->homeTeam;
    }

    public function setAwayTeam($awayTeam) {
        $this->awayTeam = $awayTeam;
    }

    public function getAwayTeam() {
        return $this->awayTeam;
    }

    public function setPlayed($played) {
        $this->played = $played;
    }

    public function getPlayed() {
        return $this->played;
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

    public function toString() {
        if ($this->getHomeScore() >= 0) {
            return $this->getHomeTeam()->getName() . ' (' . $this->getHomeScore() . ') v ' . $this->getAwayTeam()->getName() . ' (' . $this->getAwayScore() . ')';
        }
        return $this->getHomeTeam()->getName() . ' v ' . $this->getAwayTeam()->getName();
    }

}
