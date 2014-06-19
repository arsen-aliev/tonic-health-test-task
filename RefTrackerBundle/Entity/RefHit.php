<?php

namespace Ars\RefTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as AbstractUser;

/**
 * RefHit
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ars\RefTrackerBundle\Entity\RefHitRepository")
 */
class RefHit
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
     * @ORM\OneToOne(targetEntity="RefData")
     *
     */
    private $refData;

    /**
     * @ORM\ManyToOne(targetEntity="RefCode")
     *
     */
    private $refCode;


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
     * Get data
     *
     * @return RefData
     */
    public function getRefData()
    {
        return $this->refData;
    }

    /**
     * Set data
     *
     * @param $data RefData
     */
    public function setRefData(RefData $data)
    {
        $this->refData = $data;

        return $this;
    }

    /**
     * Get code
     *
     * @return RefCode
     */
    public function getRefCode()
    {
        return $this->refCode;
    }

    /**
     * Set data
     *
     * @param $data RefData
     */
    public function setRefCode(RefCode $code)
    {
        $this->refCode = $code;

        return $this;
    }

}
