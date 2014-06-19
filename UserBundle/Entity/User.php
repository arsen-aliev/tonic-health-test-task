<?php

namespace Ars\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\NotBlank(message="Please enter your firstname.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max="50",
     *     minMessage="The firstname is too short.",
     *     maxMessage="The firstname is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\NotBlank(message="Please enter your lastname.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max="50",
     *     minMessage="The lastname is too short.",
     *     maxMessage="The lastname is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $lastname;

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {

        return $this->firstname;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {

        return $this->lastname;
    }

    /**
     * Set firstname
     *
     * @param $firstname string
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Set lastname
     *
     * @param $lastname string
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Set email
     * Also copy value username/usernameCannonical
     *
     * @param $email string
     * @return User
     */
    public function setEmail($email) {

        parent::setEmail($email);
        $this->setUsername($email);
        $this->setUsernameCanonical($email);

        return $this;
    }

}
