<?php

	// src/AppBundle/Entity/StorehouseAppointment.php
	namespace AppBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;
	use Doctrine\Common\Collections\ArrayCollection;
	use Symfony\Component\Validator\Constraints as Assert;
	
	/**
	 * @ORM\Entity
	 * @ORM\Table(name="StorehouseAppointment")
	 */	 
	class StorehouseAppointment
	{	
		/**
		 * @ORM\Column(type="integer")
		 * @ORM\Id
		 * @ORM\GeneratedValue(strategy="AUTO")
		*/
		protected $id;
		
		/**
		 * @ORM\Column(type="date", nullable=true)
		 * @Assert\NotNull()
		 */
		 protected $date;

		/**
		* @ORM\ManyToOne(targetEntity="StorehouseClient", inversedBy="storehouseAppointments")
		* @ORM\JoinColumn(name="storehouseClient_id", referencedColumnName="id")
		*/
		protected $storehouseClient;
		
		/**
		 * @ORM\Column(type="string", length=100, nullable=true)
		 */
		 protected $status;

		/**
		 * @ORM\Column(type="text", nullable=true)
		 */		
		protected $note;
		
		protected $storehouseClientFirstName;
		
		protected $storehouseClientLastName;

		protected $theStorehouseClientID;


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
     * Set storehouseClient
     *
     * @param \AppBundle\Entity\StorehouseClient $storehouseClient
     *
     * @return StorehouseAppointment
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return StorehouseAppointment
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
     * Set status
     *
     * @param string $status
     *
     * @return StorehouseAppointment
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return StorehouseAppointment
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }


    /**
     * Set storehouseClientFirstName
     *
     * @param string $storehouseClientFirstName
     *
     * @return StorehouseAppointment
     */
    public function setStorehouseClientFirstName($storehouseClientFirstName)
    {
        $this->storehouseClientFirstName = $storehouseClientFirstName;

        return $this;
    }

    /**
     * Get storehouseClientFirstName
     *
     * @return string
     */
    public function getStorehouseClientFirstName()
    {
        return $this->storehouseClientFirstName;
    }

    /**
     * Set storehouseClientLastName
     *
     * @param string $storehouseClientLastName
     *
     * @return StorehouseAppointment
     */
    public function setStorehouseClientLastName($storehouseClientLastName)
    {
        $this->storehouseClientLastName = $storehouseClientLastName;

        return $this;
    }

    /**
     * Get storehouseClientLastName
     *
     * @return string
     */
    public function getStorehouseClientLastName()
    {
        return $this->storehouseClientLastName;
    }


    /**
     * Set theStorehouseClientID
     *
     * @param integer $theStorehouseClientID
     *
     * @return StorehouseAppointment
     */
    public function setTheStorehouseClientID($theStorehouseClientID)
    {
        $this->theStorehouseClientID = $theStorehouseClientID;

        return $this;
    }

    /**
     * Get theStorehouseClientID
     *
     * @return integer
     */
    public function getTheStorehouseClientID()
    {
        return $this->theStorehouseClientID;
    }
}
