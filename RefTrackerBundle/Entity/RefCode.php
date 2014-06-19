<?php

namespace Ars\RefTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as AbstractUser;

/**
 * RefCode
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ars\RefTrackerBundle\Entity\RefCodeRepository")
 */
class RefCode
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=6)
     */
    private $code;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var AbstractUser
     */
    private $user;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return RefCode
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set user
     *
     * @param $data AbstractUser
     */
    public function setUser(AbstractUser $user)
    {
        $this->user = $user;
        $this->userId = $user->getId();

        return $this;
    }
}
