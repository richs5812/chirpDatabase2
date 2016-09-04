<?php

	namespace AppBundle\Entity;
	
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Validator\Constraints as Assert;
	
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
		 * @ORM\Column(type="boolean", nullable=true)
		 */		
		protected $isVolunteer;
						
		/**
		 * @ORM\Column(type="boolean", nullable=true)
		 */		
		protected $isDonor;
		
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
		 * @ORM\Column(type="string", length=255)
		 * @Assert\NotNull()
		 */		
		protected $volunteerType;

		/**
		 * @ORM\Column(type="text", nullable=true)
		 */		
		protected $otherNotes;

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
     * Set isVolunteer
     *
     * @param boolean $isVolunteer
     *
     * @return DonorVolunteer
     */
    public function setIsVolunteer($isVolunteer)
    {
        $this->isVolunteer = $isVolunteer;

        return $this;
    }

    /**
     * Get isVolunteer
     *
     * @return boolean
     */
    public function getIsVolunteer()
    {
        return $this->isVolunteer;
    }

    /**
     * Set isDonor
     *
     * @param boolean $isDonor
     *
     * @return DonorVolunteer
     */
    public function setIsDonor($isDonor)
    {
        $this->isDonor = $isDonor;

        return $this;
    }

    /**
     * Get isDonor
     *
     * @return boolean
     */
    public function getIsDonor()
    {
        return $this->isDonor;
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
}
