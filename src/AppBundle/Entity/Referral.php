<?php

	// src/AppBundle/Entity/Referral.php
	namespace AppBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;
	use Doctrine\Common\Collections\ArrayCollection;
	use Symfony\Component\Validator\Constraints as Assert;
	
	/**
	 * @ORM\Entity
	 * @ORM\Table(name="Referral")
	 */
	 
	class Referral
	{	
		/**
		 * @ORM\Column(type="integer")
		 * @ORM\Id
		 * @ORM\GeneratedValue(strategy="AUTO")
		*/
		protected $id;
		
		/**
		 * @ORM\Column(type="string", length=1000, nullable=true)
		 */
		 protected $description;

		/**
		* @ORM\ManyToOne(targetEntity="Client", inversedBy="referrals")
		* @ORM\JoinColumn(name="client_id", referencedColumnName="id")
		*/
		protected $client;

		/**
		* @ORM\ManyToOne(targetEntity="ReferralName", inversedBy="referrals")
		*/
		protected $referralName;
		
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
     * Set description
     *
     * @param string $description
     *
     * @return Referral
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
     *
     * @return Referral
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
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Referral
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
     * Set notes
     *
     * @param string $notes
     *
     * @return Referral
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

    /**
     * Set referralName
     *
     * @param \AppBundle\Entity\ReferralName $referralName
     *
     * @return Referral
     */
    public function setReferralName(\AppBundle\Entity\ReferralName $referralName = null)
    {
        $this->referralName = $referralName;

        return $this;
    }

    /**
     * Get referralName
     *
     * @return \AppBundle\Entity\ReferralName
     */
    public function getReferralName()
    {
        return $this->referralName;
    }
}
