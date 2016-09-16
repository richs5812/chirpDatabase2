<?php

	namespace AppBundle\Entity;
	
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Validator\Constraints as Assert;
	use Doctrine\Common\Collections\ArrayCollection;

	/**
	 * @ORM\Entity
	 * @ORM\Table(name="DonorVolunteer")
	 */
	class DonorVolunteer
	{
		/**
		 * @ORM\Column(type="integer")
		 * @ORM\Id
		 * @ORM\GeneratedValue(strategy="AUTO")
		*/
		protected $id;
								
		/**
		 * @ORM\Column(type="string", length=255)
		 * @Assert\NotNull()
		 */
		protected $firstName;
		
		/**
		 * @ORM\Column(type="string", length=255)
		 * @Assert\NotNull()
		 */		
		protected $lastName;
		
		/**
		 * @ORM\Column(type="string", length=255, nullable=true)
		 */		
		protected $address;

		/**
		 * @ORM\Column(type="string", length=255, nullable=true)
		 */		
		protected $address2;
		
		/**
		 * @ORM\Column(type="integer", nullable=true)
		 * @Assert\Range(
		 *      min = 00001,
 		 *      max = 99999,
 		 *      minMessage = "Zipcode must be at least {{ limit }}",
		 *      maxMessage = "Zipcode cannot be greater than {{ limit }}"
		 * )
		 */			
		protected $zipCode;

		/**
		 * @ORM\Column(type="string", length=50, nullable=true)
		 */		
		protected $homePhoneNumber;

		/**
		 * @ORM\Column(type="string", length=50, nullable=true)
		 */		
		protected $cellPhoneNumber;
				
		/**
		 * @ORM\Column(length=255, nullable=true)
		 * @Assert\Email(
		 *     message = "The email '{{ value }}' is not a valid email.",
		 * )
		 */		
		protected $emailAddress;
		
		/**
		 * @ORM\Column(type="string", length=255, nullable=true)
		 */		
		protected $volunteerType;

		/**
		 * @ORM\Column(type="text", nullable=true)
		 */		
		protected $otherNotes;

		/**
		 * @ORM\OneToMany(targetEntity="VolunteerSession", mappedBy="donorVolunteer", cascade={"persist"})
		 * @ORM\OrderBy({"date" = "ASC"})
		 */
		protected $volunteerSessions;

		/**
		 * @ORM\OneToMany(targetEntity="Donation", mappedBy="donorVolunteer", cascade={"persist"})
		 */
		protected $donations;
				
		/**
		 * @ORM\ManyToMany(targetEntity="VolunteerCategory", cascade={"persist"})
		 */
		protected $volunteerCategories;
						
		protected $newVolunteerCategory;

		//hours for volunteer report
		protected $totalHours;

		//donations for donor/volunteer report
		protected $totalDonations;

		//most recent volunteer date for donor/volunteer report
		protected $mostRecentVolunteerDate;


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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return DonorVolunteer
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return DonorVolunteer
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return DonorVolunteer
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set address2
     *
     * @param string $address2
     *
     * @return DonorVolunteer
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set zipCode
     *
     * @param integer $zipCode
     *
     * @return DonorVolunteer
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return integer
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set homePhoneNumber
     *
     * @param string $homePhoneNumber
     *
     * @return DonorVolunteer
     */
    public function setHomePhoneNumber($homePhoneNumber)
    {
        $this->homePhoneNumber = $homePhoneNumber;

        return $this;
    }

    /**
     * Get homePhoneNumber
     *
     * @return string
     */
    public function getHomePhoneNumber()
    {
        return $this->homePhoneNumber;
    }

    /**
     * Set cellPhoneNumber
     *
     * @param string $cellPhoneNumber
     *
     * @return DonorVolunteer
     */
    public function setCellPhoneNumber($cellPhoneNumber)
    {
        $this->cellPhoneNumber = $cellPhoneNumber;

        return $this;
    }

    /**
     * Get cellPhoneNumber
     *
     * @return string
     */
    public function getCellPhoneNumber()
    {
        return $this->cellPhoneNumber;
    }

    /**
     * Set emailAddress
     *
     * @param string $emailAddress
     *
     * @return DonorVolunteer
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Get emailAddress
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Set volunteerType
     *
     * @param string $volunteerType
     *
     * @return DonorVolunteer
     */
    public function setVolunteerType($volunteerType)
    {
        $this->volunteerType = $volunteerType;

        return $this;
    }

    /**
     * Get volunteerType
     *
     * @return string
     */
    public function getVolunteerType()
    {
        return $this->volunteerType;
    }

    /**
     * Set otherNotes
     *
     * @param string $otherNotes
     *
     * @return DonorVolunteer
     */
    public function setOtherNotes($otherNotes)
    {
        $this->otherNotes = $otherNotes;

        return $this;
    }

    /**
     * Get otherNotes
     *
     * @return string
     */
    public function getOtherNotes()
    {
        return $this->otherNotes;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->volunteerSessions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->donations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add volunteerSession
     *
     * @param \AppBundle\Entity\VolunteerSession $volunteerSession
     *
     * @return DonorVolunteer
     */
    public function addVolunteerSession(\AppBundle\Entity\VolunteerSession $volunteerSession)
    {
		$volunteerSession->setDonorVolunteer($this);
        $this->volunteerSessions[] = $volunteerSession;

        return $this;
    }

    /**
     * Remove volunteerSession
     *
     * @param \AppBundle\Entity\VolunteerSession $volunteerSession
     */
    public function removeVolunteerSession(\AppBundle\Entity\VolunteerSession $volunteerSession)
    {
        $this->volunteerSessions->removeElement($volunteerSession);
    }

    /**
     * Get volunteerSessions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVolunteerSessions()
    {
        return $this->volunteerSessions;
    }

    /**
     * Add volunteerCategory
     *
     * @param \AppBundle\Entity\VolunteerCategory $volunteerCategory
     *
     * @return DonorVolunteer
     */
    public function addVolunteerCategory(\AppBundle\Entity\VolunteerCategory $volunteerCategory)
    {
        $this->volunteerCategories[] = $volunteerCategory;

        return $this;
    }

    /**
     * Remove volunteerCategory
     *
     * @param \AppBundle\Entity\VolunteerCategory $volunteerCategory
     */
    public function removeVolunteerCategory(\AppBundle\Entity\VolunteerCategory $volunteerCategory)
    {
        $this->volunteerCategories->removeElement($volunteerCategory);
    }

    /**
     * Get volunteerCategories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVolunteerCategories()
    {
        return $this->volunteerCategories;
    }
    
    public function getNewVolunteerCategory()
    {
        return $this->newVolunteerCategory;
    }
    
    public function setNewVolunteerCategory($newVolunteerCategories)
    {
//          	dump($this->getVolunteerCategories());die;
		if ($this->getVolunteerCategories() != null) {
			foreach ($this->getVolunteerCategories() as $originalCategory) {
				if($newVolunteerCategories->contains($originalCategory) == false) {
					$this->removeVolunteerCategory($originalCategory);
				}
			}
		}
		    	
    	foreach ($newVolunteerCategories as $newVolunteerCategory) {

			if($this->getVolunteerCategories()->contains($newVolunteerCategory) == false) {
				$this->addVolunteerCategory($newVolunteerCategory);
			}
    	}
    }

    /**
     * Add donation
     *
     * @param \AppBundle\Entity\Donation $donation
     *
     * @return DonorVolunteer
     */
    public function addDonation(\AppBundle\Entity\Donation $donation)
    {
		$donation->setDonorVolunteer($this);
        $this->donations[] = $donation;

        return $this;
    }

    /**
     * Remove donation
     *
     * @param \AppBundle\Entity\Donation $donation
     */
    public function removeDonation(\AppBundle\Entity\Donation $donation)
    {
        $this->donations->removeElement($donation);
    }

    /**
     * Get donations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDonations()
    {
        return $this->donations;
    }

	public function setTotalHours($totalHours)
    {
        $this->totalHours = $totalHours;

        return $this;
    }

    public function getTotalHours()
    {
        return $this->totalHours;
    }

	public function setTotalDonations($totalDonations)
    {
        $this->totalDonations = $totalDonations;

        return $this;
    }

    public function getTotalDonations()
    {
        return $this->totalDonations;
    }

	public function setMostRecentVolunteerDate($mostRecentVolunteerDate)
    {
        $this->mostRecentVolunteerDate = $mostRecentVolunteerDate;

        return $this;
    }

    public function getMostRecentVolunteerDate()
    {
        return $this->mostRecentVolunteerDate;
    }

}
