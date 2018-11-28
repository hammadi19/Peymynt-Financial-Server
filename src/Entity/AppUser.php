<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AppUserRepository")
 */
class AppUser implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="username", type="string", length=100, nullable=true)
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=150,nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="plain_password", type="string", length=255,nullable=true)
     */
    private $plainPassword;

    /**
     * @var string
     * @ORM\Column(name="first_name", type="string", length=200, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(name="last_name", type="string", length=200, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string",length=50, nullable=true)
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string",length=150, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string",length=50, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="province", type="string",length=50, nullable=true)
     */
    private $province;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string",length=50, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="post_code", type="string",length=50, nullable=true)
     */
    private $postCode;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_confirmed", type="boolean",nullable=true)
     */
    private $isConfirmed;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean",nullable=true)
     */
    private $isActive;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_archived", type="boolean",nullable=true)
     */
    private $isArchived;

    /**
     * @var string
     * @ORM\Column(name="profile_image", type="string", length=100 , nullable=true )
     */
    private $profileImage;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_no", type="string", length=100, nullable=true )
     */
    private $contactNo;

    /**
     * @var string
     * @ORM\Column(name="about", type="string", length=250, nullable=true)
     */
    private $about;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_birth", type="datetime", nullable=true)
     */
    private $dateOfBirth;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_login_date", type="datetime", nullable=true)
     */
    private $lastLoginDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AppUserLink", mappedBy="user", fetch="EXTRA_LAZY")
     */
    private $userLinks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserGroup", mappedBy="user",fetch="EXTRA_LAZY")
     */
    private $userGroups;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Business", mappedBy="user", fetch="EXTRA_LAZY")
     */
    private $businesses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Setting", mappedBy="user", fetch="EXTRA_LAZY")
     */
    private $settings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserEmailAccount", mappedBy="user", fetch="EXTRA_LAZY")
     */
    private $userEmailAccounts;

    /**
     * set start ups
     */
    public function __construct(){
        $this->isActive                 = true;
        $this->createdDate              = new \DateTime('now');
        $this->isConfirmed              = false;
        $this->userLinks                = new ArrayCollection();
        $this->userGroups               = new ArrayCollection();
        $this->businesses               = new ArrayCollection();
        $this->settings                 = new ArrayCollection();
        $this->userEmailAccounts        = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return AppUser
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return AppUser
     */
    public function setEmail($email)
    {
        $this->email = $email;
        $this->setUsername($email);

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * Set password
     *
     * @param string $password
     *
     * @return AppUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password
     *
     * @param string $plainPassword
     *
     * @return AppUser
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return AppUser
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return AppUser
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return AppUser
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set postCode
     *
     * @param string $postCode
     *
     * @return AppUser
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;

        return $this;
    }

    /**
     * Get postCode
     *
     * @return string
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return AppUser
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param string $province
     */
    public function setProvince($province)
    {
        $this->province = $province;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Set about
     *
     * @param string $about
     *
     * @return AppUser
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * Set isConfirmed
     *
     * @param boolean $isConfirmed
     *
     * @return AppUser
     */
    public function setIsConfirmed($isConfirmed)
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }

    /**
     * Get isConfirmed
     *
     * @return bool
     */
    public function getIsConfirmed()
    {
        return $this->isConfirmed;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Users
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set isAcrchived
     *
     * @param boolean $isArchived
     *
     * @return AppUser
     */
    public function setIsArchived($isArchived)
    {
        $this->isArchived = $isArchived;

        return $this;
    }

    /**
     * Get isArchived
     *
     * @return bool
     */
    public function getIsArchived()
    {
        return $this->isArchived;
    }

    /**
     * Set profileImage
     *
     * @param string $profileImage
     *
     * @return AppUser
     */
    public function setProfileImage($profileImage)
    {
        $this->profileImage = $profileImage;

        return $this;
    }


    /**
     * Get profileImage
     * @return string
     */
    public function getProfileImage()
    {
        return $this->profileImage;
    }

    /**
     * Set contactNo
     *
     * @param string $contactNo
     *
     * @return AppUser
     */
    public function setContactNo($contactNo)
    {
        $this->contactNo = $contactNo;

        return $this;
    }

    /**
     * Get contactNo
     *
     * @return string
     */
    public function getContactNo()
    {
        return $this->contactNo;
    }

    /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     *
     * @return AppUser
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }



    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return AppUser
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set lastLoginDate
     *
     * @param \DateTime $lastLoginDate
     *
     * @return AppUser
     */
    public function setLastLoginDate($lastLoginDate)
    {
        $this->lastLoginDate = $lastLoginDate;

        return $this;
    }

    /**
     * Get lastLoginDate
     *
     * @return \DateTime
     */
    public function getLastLoginDate()
    {
        return $this->lastLoginDate;
    }

    public function getSalt()
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function getBusinesses()
    {
        return $this->businesses;
    }

    /**
     * @param mixed $businesses
     */
    public function setBusinesses($businesses): void
    {
        $this->businesses = $businesses;
    }

    public function getRoles()
    {
        $roles = $this->userGroups;
        $stringRoles = array();
        array_push($stringRoles, 'ROLE_USER');
        foreach ($roles as $group) {
            array_push($stringRoles ,$group->getGroup()->getName());
        }
        return array_unique($stringRoles);
    }

    public function eraseCredentials()
    {}


    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

}//@
