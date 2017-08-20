<?php

	// src/AppBundle/Entity/StorehouseReferral.php
	namespace AppBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;
	use Doctrine\Common\Collections\ArrayCollection;
	use Symfony\Component\Validator\Constraints as Assert;
	
	/**
	 * @ORM\Entity
	 * @ORM\Table(name="StorehouseReferral")
	 */
	class StorehouseReferral
	{	
		/**
		 * @ORM\Column(type="integer")
		 * @ORM\Id
		 * @ORM\GeneratedValue(strategy="AUTO")
		*/
		protected $id;
		
		/**
		 * @ORM\Column(type="string", length=100, nullable=true)
		 */
		 protected $type;

		/**
		* @ORM\ManyToOne(targetEntity="StorehouseClient", inversedBy="storehouseReferrals")
		* @ORM\JoinColumn(name="storehouseClient_id", referencedColumnName="id")
		*/
		protected $storehouseClient;
		
		/**
		 * @ORM\Column(type="date", nullable=true)
		 */		
		protected $date;
		
		/**
		 * @ORM\Column(type="text", nullable=true)
		 */		
		protected $notes;


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
     * Set type
     *
     * @param string $type
     *
     * @return StorehouseReferral
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return StorehouseReferral
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
     * Set storehouseClient
     *
     * @param \AppBundle\Entity\StorehouseClient $storehouseClient
     *
     * @return StorehouseReferral
     */
    public function setStorehouseClient(\AppBundle\Entity\StorehouseClient $storehouseClient = null)
    {
        $this->storehouseClient = $storehouseClient;

        return $this;
    }

    /**
     * Get storehouseClient
     *
     * @return \AppBundle\Entity\StorehouseClient
     */
    public function getStorehouseClient()
    {
        return $this->storehouseClient;
    }

    /**
     * Set notes
     *
     * @param string $notes
     *
     * @return StorehouseReferral
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }
}
