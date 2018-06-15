<?php

namespace Todo\Bundle\ListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Item
 *
 * @ORM\Table(name="items")
 * @ORM\Entity
 */
class Item
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    protected $content;

    /**
     * @ORM\Column(type="integer")
     */
    protected $order;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;


    /**
     * @ORM\ManyToOne(targetEntity="ListItem", inversedBy="items")
     * @ORM\JoinColumn(name="list_id", referencedColumnName="id", onDelete="CASCADE")
     */

    protected $list;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @param mixed $list
     */
    public function setList(ListItem $list)
    {
        $this->list = $list;
    }

    /**
     * @return mixed
     */
    public function getList()
    {
        return $this->list;
    }

}
