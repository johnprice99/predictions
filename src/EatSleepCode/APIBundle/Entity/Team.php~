<?php

namespace EatSleepCode\APIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(indexes={@ORM\Index(name="url_idx", columns={"url"})})
 * @ORM\Entity(repositoryClass="EatSleepCode\APIBundle\Repository\Team")
 */
class Team {

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
     * @ORM\Column(type="string", length=50)
     */
    protected $name;

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

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }
}
