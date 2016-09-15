<?php

	namespace AppBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Validator\Constraints as Assert;
	
	/**
	 * @ORM\Entity
	 * @ORM\Table(name="Donation")
	 */
	 
	class Donation
	{	
		/**
		 * @ORM\Column(type="integer")
		 * @ORM\Id
		 * @ORM\GeneratedValue(strategy="AUTO")
		*/
		protected $id;
		
		/**
		 * @ORM\Column(type="date", nullable=true)
		 */		
		protected $date;

		/**
		 * @ORM\Column(type="decimal", scale=2, nullable=true)
		 */			
		protected $amount;

		/**
		 * @ORM\Column(type="string", length=255, nullable=true)
		 */
		 protected $paymentType;
		
		/**
		 * @ORM\Column(type="text", nullable=true)
		 */		
		protected $note;

		/**
		 * @ORM\ManyToOne(targetEntity="DonorVolunteer", inversedBy="donations")
		 */
		protected $donorVolunteer;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Donation
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
     * Set amount
     *
     * @param string $amount
     *
     * @return Donation
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set paymentType
     *
     * @param string $paymentType
     *
     * @return Donation
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * Get paymentType
     *
     * @return string
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Donation
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
     * Set donorVolunteer
     *
     * @param \AppBundle\Entity\DonorVolunteer $donorVolunteer
     *
     * @return Donation
     */
    public function setDonorVolunteer(\AppBundle\Entity\DonorVolunteer $donorVolunteer = null)
    {
        $this->donorVolunteer = $donorVolunteer;

        return $this;
    }

    /**
     * Get donorVolunteer
     *
     * @return \AppBundle\Entity\DonorVolunteer
     */
    public function getDonorVolunteer()
    {
        return $this->donorVolunteer;
    }
}
