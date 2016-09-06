<?php

	namespace AppBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Validator\Constraints as Assert;
	
	/**
	 * @ORM\Entity
	 * @ORM\Table(name="VolunteerSession")
	 */
	 
	class VolunteerSession
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
		 * @ORM\Column(type="integer", nullable=true)
		 */			
		protected $hours;

		/**
		 * @ORM\Column(type="text", nullable=true)
		 */		
		protected $note;
		
		/**
		 * @ORM\ManyToOne(targetEntity="DonorVolunteer", inversedBy="volunteerSessions")
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
     * @return VolunteerSession
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
     * Set hours
     *
     * @param integer $hours
     *
     * @return VolunteerSession
     */
    public function setHours($hours)
    {
        $this->hours = $hours;

        return $this;
    }

    /**
     * Get hours
     *
     * @return integer
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return VolunteerSession
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
     * @return VolunteerSession
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
