<?php

namespace EatSleepCode\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\MappedSuperclass
 */
class User extends BaseUser {

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=100)
	 * @Assert\NotBlank(groups={"Registration", "Profile"})
	 * @Assert\Length(
	 *      min = "2",
	 *      max = "100",
	 *      minMessage = "The first name must be at least {{ limit }} characters long",
	 *      maxMessage = "The first name cannot be longer than {{ limit }} characters",
	 *      groups={"Registration", "Profile"}
	 * )
	 */
	protected $firstName;

	/**
	 * @ORM\Column(type="string", length=100)
	 * @Assert\NotBlank(groups={"Registration", "Profile"})
	 * @Assert\Length(
	 *      min = "2",
	 *      max = "100",
	 *      minMessage = "The last name must be at least {{ limit }} characters long",
	 *      maxMessage = "The last name cannot be longer than {{ limit }} characters",
	 *      groups={"Registration", "Profile"}
	 * )
	 */
	protected $lastName;

	/**
	 * @Assert\File(maxSize="2048k")
	 * @Assert\Image(mimeTypesMessage="Please upload a valid image")
	 */
	protected $profilePictureFile;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $profilePicturePath;

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setFirstName($firstName) {
		$this->firstName = $firstName;
	}

	public function getFirstName() {
		return $this->firstName;
	}

	public function setLastName($lastName) {
		$this->lastName = $lastName;
	}

	public function getLastName() {
		return $this->lastName;
	}

	public function getFullName() {
		return $this->firstName . ' ' . $this->lastName;
	}

	/*
	 * This function is here so that a user uses their email address as the username (due to FOSUserBundle)
	 */
	public function setEmail($email) {
		$email = is_null($email) ? '' : $email;
		parent::setEmail($email);
		$this->setUsername($email);
	}

	//used to flag in the controller to remove the avatar
	public $clearProfilePicture;

	public function setProfilePicturePath($profilePicturePath) {
		$this->profilePicturePath = $profilePicturePath;
	}

	public function getProfilePicturePath() {
		return $this->profilePicturePath;
	}

	public function setProfilePictureFile(UploadedFile $file = null) {
		$this->profilePictureFile = $file;
	}

	public function getProfilePictureFile() {
		return $this->profilePictureFile;
	}

	public function getAvatarURL($size=100) {
		if ($this->getProfilePicturePath() !== null) {
			return 'http://localhost/ideaapp/web/uploads/avatars/' . $this->getId() . '/' . $this->getProfilePicturePath();
		}
		return $this->getGravatarURL($size);
	}

	public function getGravatarURL($size=100) {
		return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->getEmail()))) . '?s=' . $size . '&d=mm';
	}
}
