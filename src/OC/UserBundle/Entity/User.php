<?php

namespace OC\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use OC\PlatformBundle\Entity\Advert;

/**
 * User
 *
 * @ORM\Table(name="oc_user")
 * @ORM\Entity(repositoryClass="OC\UserBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="OC\PlatformBundle\Entity\Advert", mappedBy="user")
     */
    private $adverts;

    public function __construct()
    {
        parent::__construct();
        $this->adverts = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getAdverts()
    {
        return $this->adverts;
    }

    public function addAdvert(Advert $advert)
    {
        $this->adverts[] = $advert;

        $advert->setUser($this);

        return $this;
    }

    public function removeAdvert(Advert $advert)
    {
        $this->adverts->removeElement($advert);
    }

    /**
     * @ORM\PrePersist
     */
    public function defaultRole()
    {
        $this->roles = array('ROLE_AUTEUR');
    }
}