<?php

namespace Ars\RefTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as AbstractUser;

/**
 * RefUser
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ars\RefTrackerBundle\Entity\RefUserRepository")
 */
class RefUser
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
     * @var AbstractUser
     */
    private $refUser;

    /**
     * @var integer
     *
     * @ORM\Column(name="refUser_id", type="integer")
     */
    private $refUserId;

    /**
     * @ORM\OneToOne(targetEntity="RefHit")
     *
     */
    private $refHit;


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
     * Get refHit
     *
     * @return RefHit
     */
    public function getRefHit()
    {
        return $this->refHit;
    }

    /**
     * Set refHit
     *
     * @param $hit RefHit
     */
    public function setRefHit(RefHit $hit)
    {
        $this->refHit = $hit;

        return $this;
    }

    /**
     * Get refUserId
     *
     * @return integer
     */
    public function getRefUserId()
    {
        return $this->refUserId;
    }

    /**
     * Set refUser
     *
     * @param $data AbstractUser
     */
    public function setRefUser(AbstractUser $user)
    {
        $this->refUser = $user;
        $this->refUserId = $user->getId();

        return $this;
    }

}
