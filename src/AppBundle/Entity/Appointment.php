<?php

	// src/AppBundle/Entity/Appointment.php
	namespace AppBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;
	use Doctrine\Common\Collections\ArrayCollection;
	use Symfony\Component\Validator\Constraints as Assert;
	use DoctrineEncrypt\Configuration\Encrypted;
	
	/**
	 * @ORM\Entity
	 * @ORM\Table(name="Appointment")
	 */
	 
	class Appointment
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
		* @ORM\ManyToOne(targetEntity="Client", inversedBy="appointments")
		* @ORM\JoinColumn(name="client_id", referencedColumnName="id")
		*/
		protected $client;
		
		/**
		 * @ORM\Column(type="string", length=100, nullable=true)
		 * @Encrypted
		 */
		 protected $status;

		/**
		 * @ORM\Column(type="text", nullable=true)
		 * @Encrypted
		 */		
		protected $note;
		
		protected $clientFirstName;

		protected $clientLastName;

		protected $theClientID;


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
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Appointment
     */
    public function setClient(\AppBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \AppBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Appointment
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
     * @return Appointment
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
     * @return Appointment
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
     * Set clientFirstName
     *
     * @param string $clientFirstName
     *
     * @return Appointment
     */
    public function setClientFirstName($clientFirstName)
    {
        $this->clientFirstName = $clientFirstName;

        return $this;
    }

    /**
     * Get clientFirstName
     *
     * @return string
     */
    public function getClientFirstName()
    {
        return $this->clientFirstName;
    }

    /**
     * Set clientLastName
     *
     * @param string $clientLastName
     *
     * @return Appointment
     */
    public function setClientLastName($clientLastName)
    {
        $this->clientLastName = $clientLastName;

        return $this;
    }

    /**
     * Get clientLastName
     *
     * @return string
     */
    public function getClientLastName()
    {
        return $this->clientLastName;
    }


    /**
     * Set theClientID
     *
     * @param integer $theClientID
     *
     * @return Appointment
     */
    public function setTheClientID($theClientID)
    {
        $this->theClientID = $theClientID;

        return $this;
    }

    /**
     * Get theClientID
     *
     * @return integer
     */
    public function getTheClientID()
    {
        return $this->theClientID;
    }
}
