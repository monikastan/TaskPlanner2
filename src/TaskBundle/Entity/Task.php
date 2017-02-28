<?php

namespace TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="TaskBundle\Repository\TaskRepository")
 */
class Task
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=255)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deadline", type="date")
     */
    private $deadline;
      
    /**
     * @var string
     *
     * @ORM\Column(name="attach", type="string", length=255, nullable=true)
     */
    
    private $attach;
    
    /**
     * @ORM\ManyToOne(targetEntity="Status", inversedBy="tasks")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;
    
    /**
     * @ORM\ManyToOne(targetEntity="Priority", inversedBy="tasks")
     * @ORM\JoinColumn(name="priority_id", referencedColumnName="id")
     */
    private $priority;


    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="tasks")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;
    
    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="task")
     */
    private $comments;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tasks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
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
     * Set name
     *
     * @param string $name
     * @return Task
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Task
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Task
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set deadline
     *
     * @param \DateTime $deadline
     * @return Task
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get deadline
     *
     * @return \DateTime 
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * Set priority
     *
     * @param \TaskBundle\Entity\Priority $priority
     * @return Task
     */
    public function setPriority(\TaskBundle\Entity\Priority $priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return \TaskBundle\Entity\Priority 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set attach
     *
     * @param string $attach
     * @return Task
     */
    public function setAttach($attach)
    {
        $this->attach = $attach;

        return $this;
    }

    /**
     * Get attach
     *
     * @return string 
     */
    public function getAttach()
    {
        return $this->attach;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set category
     *
     * @param \TaskBundle\Entity\Category $category
     * @return Task
     */
    public function setCategory(\TaskBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \TaskBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add comments
     *
     * @param \TaskBundle\Entity\Comment $comments
     * @return Task
     */
    public function addComment(\TaskBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \TaskBundle\Entity\Comment $comments
     */
    public function removeComment(\TaskBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set user
     *
     * @param \TaskBundle\Entity\User $user
     * @return Task
     */
    public function setUser(\TaskBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \TaskBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set status
     *
     * @param \TaskBundle\Entity\Status $status
     * @return Task
     */
    public function setStatus(\TaskBundle\Entity\Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \TaskBundle\Entity\Status 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
