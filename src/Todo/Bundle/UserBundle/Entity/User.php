<?php

namespace Todo\Bundle\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Todo\Bundle\ListBundle\Entity\ListItem;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Group")
     * @ORM\JoinTable(name="users_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @ORM\OneToMany(targetEntity="Todo\Bundle\ListBundle\Entity\ListItem", mappedBy="user")
     */
    protected $lists;

    /**
     * @return ArrayCollection|ListItem[]
     */
    public function getLists()
    {
        return $this->lists;
    }

    public function __construct()
    {
        parent::__construct();
        $this->lists = new ArrayCollection();
    }
}