<?php

	// src/AppBundle/Entity/Client.php
	namespace AppBundle\Entity;
	
	use Doctrine\ORM\Mapping as ORM;
	use Doctrine\Common\Collections\ArrayCollection;
	use Symfony\Component\Validator\Constraints as Assert;
	use DoctrineEncrypt\Configuration\Encrypted;
	
	/**
	 * @ORM\Entity
	 * @ORM\Table(name="Client")
	 */
	class Client
	{	
		/**
		 * @ORM\OneToMany(targetEntity="FamilyMember", mappedBy="client", cascade={"persist"})
		 */
		protected $familyMembers;
		
		/**
		 * @ORM\OneToMany(targetEntity="Referral", mappedBy="client", cascade={"persist"})
		 */
		protected $referrals;
		
		/**
		 * @ORM\OneToMany(targetEntity="Appointment", mappedBy="client", cascade={"persist"})
		 */
		protected $appointments;
	
		/**
		 * @ORM\Column(type="integer")
		 * @ORM\Id
		 * @ORM\GeneratedValue(strategy="AUTO")
		*/
		protected $id;
		
		/**
		 * @ORM\Column(type="string", length=255)
		 * @Assert\NotNull()
		 * @Encrypted
		 */
		protected $firstName;
		
		/**
		 * @ORM\Column(type="string", length=255)
		 * @Assert\NotNull()
		 * @Encrypted
		 */		
		protected $lastName;
		
		/**
		 * @ORM\Column(type="string", length=255, nullable=true)
		 * @Encrypted
		 */		
		protected $address;

		/**
		 * @ORM\Column(type="string", length=255, nullable=true)
		 * @Encrypted
		 */		
		protected $address2;
		
		/**
		 * @ORM\Column(type="string", length=50, nullable=true)
		 * @Encrypted
		 */		
		protected $homePhoneNumber;

		/**
		 * @ORM\Column(type="string", length=50, nullable=true)
		 * @Encrypted
		 */		
		protected $cellPhoneNumber;
		
		/**
		 * @ORM\Column(type="integer", nullable=true)
		 * @Assert\Range(
		 *      min = 0,
 		 *      max = 120,
 		 *      minMessage = "Age must be at least {{ limit }} years",
		 *      maxMessage = "Age cannot be greater than {{ limit }} years"
		 * )
		 */			
		protected $age;
		
		/**
		 * @ORM\Column(type="string", length=50, nullable=true)
		 * @Encrypted
		 */		
		protected $gender;
		
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
		 * @ORM\Column(type="boolean", nullable=true)
		 */		
		protected $isPregnant;
				
		/**
		 * @ORM\Column(type="date", nullable=true)
		 */		
		protected $enrollmentDate;
		
		/**
		 * @ORM\Column(type="boolean", nullable=true)
		 */		
		protected $addressVerified;

		/**
		 * @ORM\Column(length=255, nullable=true)
		 * @Assert\Email(
		 *     message = "The email '{{ value }}' is not a valid email.",
		 *     checkMX = true
		 * )
		 * @Encrypted
		 */		
		protected $emailAddress;
		
		/**
		 * @ORM\Column(type="integer", nullable=true)
		 * @Assert\Range(
		 *      min = 0,
 		 *      max = 30,
 		 *      minMessage = "Family Size must be at least {{ limit }} family members",
		 *      maxMessage = "Family Size cannot be greater than {{ limit }} family members"
		 * )
		 */			
		protected $familySize;

		/**
		 * @ORM\Column(type="integer", nullable=true)
		 * @Assert\Range(
		 *      min = 0,
 		 *      max = 30,
 		 *      minMessage = "Number of adults must be at least {{ limit }}",
		 *      maxMessage = "Number of adults cannot be greater than {{ limit }}"
		 * )
		 */			
		protected $adultsNumber;

		/**
		 * @ORM\Column(type="integer", nullable=true)
		 * @Assert\Range(
		 *      min = 0,
 		 *      max = 30,
 		 *      minMessage = "Number of adults must be at least {{ limit }}",
		 *      maxMessage = "Number of adults cannot be greater than {{ limit }}"
		 * )
		 */			
		protected $childrenNumber;
		
		/**
		 * @ORM\Column(type="integer", nullable=true)
		 * @Assert\Range(
		 *      min = 0,
 		 *      max = 30,
 		 *      minMessage = "Number of family members in age range must be at least {{ limit }}",
		 *      maxMessage = "Number of family members in age range cannot be greater than {{ limit }}"
		 * )
		 */			
		protected $ageRange05;
		
		/**
		 * @ORM\Column(type="integer", nullable=true)
		 * @Assert\Range(
		 *      min = 0,
 		 *      max = 30,
 		 *      minMessage = "Number of family members in age range must be at least {{ limit }}",
		 *      maxMessage = "Number of family members in age range cannot be greater than {{ limit }}"
		 * )
		 */			
		protected $ageRange617;
		
		/**
		 * @ORM\Column(type="integer", nullable=true)
		 * @Assert\Range(
		 *      min = 0,
 		 *      max = 30,
 		 *      minMessage = "Number of family members in age range must be at least {{ limit }}",
		 *      maxMessage = "Number of family members in age range cannot be greater than {{ limit }}"
		 * )
		 */			
		protected $ageRange1829;
		
		/**
		 * @ORM\Column(type="integer", nullable=true)
		 * @Assert\Range(
		 *      min = 0,
 		 *      max = 30,
 		 *      minMessage = "Number of family members in age range must be at least {{ limit }}",
		 *      maxMessage = "Number of family members in age range cannot be greater than {{ limit }}"
		 * )
		 */			
		protected $ageRange3039;
		
		/**
		 * @ORM\Column(type="integer", nullable=true)
		 * @Assert\Range(
		 *      min = 0,
 		 *      max = 30,
 		 *      minMessage = "Number of family members in age range must be at least {{ limit }}",
		 *      maxMessage = "Number of family members in age range cannot be greater than {{ limit }}"
		 * )
		 */			
		protected $ageRange4049;
		
		/**
		 * @ORM\Column(type="integer", nullable=true)
		 * @Assert\Range(
		 *      min = 0,
 		 *      max = 30,
 		 *      minMessage = "Number of family members in age range must be at least {{ limit }}",
		 *      maxMessage = "Number of family members in age range cannot be greater than {{ limit }}"
		 * )
		 */			
		protected $ageRange5064;

		/**
		 * @ORM\Column(type="integer", nullable=true)
		 * @Assert\Range(
		 *      min = 0,
 		 *      max = 30,
 		 *      minMessage = "Number of family members in age range must be at least {{ limit }}",
		 *      maxMessage = "Number of family members in age range cannot be greater than {{ limit }}"
		 * )
		 */			
		protected $ageRange65;

		/**
		 * @ORM\Column(type="boolean", nullable=true)
		 */		
		protected $stoveYes;
		
		/**
		 * @ORM\Column(type="boolean", nullable=true)
		 */		
		protected $stoveNo;

		/**
		 * @ORM\Column(type="boolean", nullable=true)
		 */		
		protected $stateEmergencyRelease;
		
		/**
		 * @ORM\Column(type="boolean", nullable=true)
		 */		
		protected $foodStampAssistance;
		
		/**
		 * @ORM\Column(type="boolean", nullable=true)
		 */		
		protected $limitedHealthServicesReferral;

		/**
		 * @ORM\Column(length=1000, nullable=true)
		 * @Encrypted
		 */		
		protected $additionalServices;
		
		/**
		 * @ORM\Column(type="text", nullable=true)
		 * @Encrypted
		 */		
		protected $otherNotes;

		/**
		 * @ORM\Column(type="boolean", nullable=true)
		 */		
		protected $coatOrder;
		
		/**
		 * @ORM\Column(type="boolean", nullable=true)
		 */		
		protected $previousChristmasFoodYes;
		
		/**
		 * @ORM\Column(type="boolean", nullable=true)
		 */		
		protected $previousChristmasFoodNo;
			
		/**
		 * @ORM\Column(type="date", nullable=true)
		 */		
		protected $coatOrderDate;
		
		/**
		 * @ORM\Column(type="boolean", nullable=true)
		 */		
		protected $childcareServices;
		
		/**
		 * @ORM\Column(type="boolean", nullable=true)
		 */		
		protected $heatShutoff;
		
		/**
		 * @ORM\Column(type="boolean", nullable=true)
		 */		
		protected $lightShutoff;
		
		/**
		 * @ORM\Column(type="boolean", nullable=true)
		 */		
		protected $waterShutoff;

		/**
		 * @ORM\Column(length=1000, nullable=true)
		 * @Encrypted
		 */		
		protected $otherShutoff;
		
		/**
		 * @ORM\Column(type="boolean", nullable=true)
		 */		
		protected $taxesDifficulty;
		
		/**
		 * @ORM\Column(type="boolean", nullable=true)
		 */		
		protected $foreclosureNotice;
		
		/**
		 * @ORM\Column(type="boolean", nullable=true)
		 */		
		protected $landlordEviction;

		/**
		 * @ORM\Column(length=1000, nullable=true)
		 * @Encrypted
		 */		
		protected $otherHousingIssue;

		
	public function __construct()
	{
		$this->familyMembers = new ArrayCollection();
		$this->referrals = new ArrayCollection();
		$this->appointments = new ArrayCollection();
	}
	
	public function getFamilyMembers()
    {
        return $this->familyMembers;
    }
	
    public function addFamilyMember(FamilyMember $familyMember)
    {
        $familyMember->setClient($this);
    
        $this->familyMembers->add($familyMember);
    }

    public function removeFamilyMember(FamilyMember $familyMember)
    {
        $this->familyMembers->removeElement($familyMember);
    }
	
	public function getReferrals()
    {
        return $this->referrals;
    }
	
    public function addReferral(Referral $referral)
    {
        $referral->setClient($this);
    
        $this->referrals->add($referral);
    }

    public function removeReferral(Referral $referral)
    {
        $this->referrals->removeElement($referral);
    }
	
		public function getAppointments()
    {
        return $this->appointments;
    }
	
    public function addAppointment(Appointment $appointment)
    {
        $appointment->setClient($this);
    
        $this->appointments->add($appointment);
    }

    public function removeAppointment(Appointment $appointment)
    {
        $this->appointments->removeElement($appointment);
    }
    
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
     * @return Client
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
     * @return Client
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
     * Set age
     *
     * @param integer $age
     *
     * @return Client
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Client
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }


    /**
     * Set address
     *
     * @param string $address
     *
     * @return Client
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
     * @return Client
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
     * Set homePhoneNumber
     *
     * @param string $homePhoneNumber
     *
     * @return Client
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
     * @return Client
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
     * Set zipCode
     *
     * @param integer $zipCode
     *
     * @return Client
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
     * Set isPregnant
     *
     * @param boolean $isPregnant
     *
     * @return Client
     */
    public function setIsPregnant($isPregnant)
    {
        $this->isPregnant = $isPregnant;

        return $this;
    }

    /**
     * Get isPregnant
     *
     * @return boolean
     */
    public function getIsPregnant()
    {
        return $this->isPregnant;
    }

    /**
     * Set enrollmentDate
     *
     * @param \DateTime $enrollmentDate
     *
     * @return Client
     */
    public function setEnrollmentDate($enrollmentDate)
    {
        $this->enrollmentDate = $enrollmentDate;

        return $this;
    }

    /**
     * Get enrollmentDate
     *
     * @return \DateTime
     */
    public function getEnrollmentDate()
    {
        return $this->enrollmentDate;
    }

    /**
     * Set addressVerified
     *
     * @param boolean $addressVerified
     *
     * @return Client
     */
    public function setAddressVerified($addressVerified)
    {
        $this->addressVerified = $addressVerified;

        return $this;
    }

    /**
     * Get addressVerified
     *
     * @return boolean
     */
    public function getAddressVerified()
    {
        return $this->addressVerified;
    }

    /**
     * Set emailAddress
     *
     * @param string $emailAddress
     *
     * @return Client
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
     * Set familySize
     *
     * @param integer $familySize
     *
     * @return Client
     */
    public function setFamilySize($familySize)
    {
        $this->familySize = $familySize;

        return $this;
    }

    /**
     * Get familySize
     *
     * @return integer
     */
    public function getFamilySize()
    {
        return $this->familySize;
    }

    /**
     * Set adultsNumber
     *
     * @param integer $adultsNumber
     *
     * @return Client
     */
    public function setAdultsNumber($adultsNumber)
    {
        $this->adultsNumber = $adultsNumber;

        return $this;
    }

    /**
     * Get adultsNumber
     *
     * @return integer
     */
    public function getAdultsNumber()
    {
        return $this->adultsNumber;
    }

    /**
     * Set ageRange05
     *
     * @param integer $ageRange05
     *
     * @return Client
     */
    public function setAgeRange05($ageRange05)
    {
        $this->ageRange05 = $ageRange05;

        return $this;
    }

    /**
     * Get ageRange05
     *
     * @return integer
     */
    public function getAgeRange05()
    {
        return $this->ageRange05;
    }

    /**
     * Set ageRange617
     *
     * @param integer $ageRange617
     *
     * @return Client
     */
    public function setAgeRange617($ageRange617)
    {
        $this->ageRange617 = $ageRange617;

        return $this;
    }

    /**
     * Get ageRange617
     *
     * @return integer
     */
    public function getAgeRange617()
    {
        return $this->ageRange617;
    }

    /**
     * Set ageRange1829
     *
     * @param integer $ageRange1829
     *
     * @return Client
     */
    public function setAgeRange1829($ageRange1829)
    {
        $this->ageRange1829 = $ageRange1829;

        return $this;
    }

    /**
     * Get ageRange1829
     *
     * @return integer
     */
    public function getAgeRange1829()
    {
        return $this->ageRange1829;
    }

    /**
     * Set ageRange3039
     *
     * @param integer $ageRange3039
     *
     * @return Client
     */
    public function setAgeRange3039($ageRange3039)
    {
        $this->ageRange3039 = $ageRange3039;

        return $this;
    }

    /**
     * Get ageRange3039
     *
     * @return integer
     */
    public function getAgeRange3039()
    {
        return $this->ageRange3039;
    }

    /**
     * Set ageRange4049
     *
     * @param integer $ageRange4049
     *
     * @return Client
     */
    public function setAgeRange4049($ageRange4049)
    {
        $this->ageRange4049 = $ageRange4049;

        return $this;
    }

    /**
     * Get ageRange4049
     *
     * @return integer
     */
    public function getAgeRange4049()
    {
        return $this->ageRange4049;
    }

    /**
     * Set ageRange5064
     *
     * @param integer $ageRange5064
     *
     * @return Client
     */
    public function setAgeRange5064($ageRange5064)
    {
        $this->ageRange5064 = $ageRange5064;

        return $this;
    }

    /**
     * Get ageRange5064
     *
     * @return integer
     */
    public function getAgeRange5064()
    {
        return $this->ageRange5064;
    }

    /**
     * Set ageRange65
     *
     * @param integer $ageRange65
     *
     * @return Client
     */
    public function setAgeRange65($ageRange65)
    {
        $this->ageRange65 = $ageRange65;

        return $this;
    }

    /**
     * Get ageRange65
     *
     * @return integer
     */
    public function getAgeRange65()
    {
        return $this->ageRange65;
    }

    /**
     * Set stoveYes
     *
     * @param boolean $stoveYes
     *
     * @return Client
     */
    public function setStoveYes($stoveYes)
    {
        $this->stoveYes = $stoveYes;

        return $this;
    }

    /**
     * Get stoveYes
     *
     * @return boolean
     */
    public function getStoveYes()
    {
        return $this->stoveYes;
    }

    /**
     * Set stoveNo
     *
     * @param boolean $stoveNo
     *
     * @return Client
     */
    public function setStoveNo($stoveNo)
    {
        $this->stoveNo = $stoveNo;

        return $this;
    }

    /**
     * Get stoveNo
     *
     * @return boolean
     */
    public function getStoveNo()
    {
        return $this->stoveNo;
    }

    /**
     * Set stateEmergencyRelease
     *
     * @param boolean $stateEmergencyRelease
     *
     * @return Client
     */
    public function setStateEmergencyRelease($stateEmergencyRelease)
    {
        $this->stateEmergencyRelease = $stateEmergencyRelease;

        return $this;
    }

    /**
     * Get stateEmergencyRelease
     *
     * @return boolean
     */
    public function getStateEmergencyRelease()
    {
        return $this->stateEmergencyRelease;
    }

    /**
     * Set foodStampAssistance
     *
     * @param boolean $foodStampAssistance
     *
     * @return Client
     */
    public function setFoodStampAssistance($foodStampAssistance)
    {
        $this->foodStampAssistance = $foodStampAssistance;

        return $this;
    }

    /**
     * Get foodStampAssistance
     *
     * @return boolean
     */
    public function getFoodStampAssistance()
    {
        return $this->foodStampAssistance;
    }

    /**
     * Set limitedHealthServicesReferral
     *
     * @param boolean $limitedHealthServicesReferral
     *
     * @return Client
     */
    public function setLimitedHealthServicesReferral($limitedHealthServicesReferral)
    {
        $this->limitedHealthServicesReferral = $limitedHealthServicesReferral;

        return $this;
    }

    /**
     * Get limitedHealthServicesReferral
     *
     * @return boolean
     */
    public function getLimitedHealthServicesReferral()
    {
        return $this->limitedHealthServicesReferral;
    }

    /**
     * Set additionalServices
     *
     * @param string $additionalServices
     *
     * @return Client
     */
    public function setAdditionalServices($additionalServices)
    {
        $this->additionalServices = $additionalServices;

        return $this;
    }

    /**
     * Get additionalServices
     *
     * @return string
     */
    public function getAdditionalServices()
    {
        return $this->additionalServices;
    }

    /**
     * Set otherNotes
     *
     * @param string $otherNotes
     *
     * @return Client
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
     * Set coatOrder
     *
     * @param boolean $coatOrder
     *
     * @return Client
     */
    public function setCoatOrder($coatOrder)
    {
        $this->coatOrder = $coatOrder;

        return $this;
    }

    /**
     * Get coatOrder
     *
     * @return boolean
     */
    public function getCoatOrder()
    {
        return $this->coatOrder;
    }

    /**
     * Set previousChristmasFoodYes
     *
     * @param boolean $previousChristmasFoodYes
     *
     * @return Client
     */
    public function setPreviousChristmasFoodYes($previousChristmasFoodYes)
    {
        $this->previousChristmasFoodYes = $previousChristmasFoodYes;

        return $this;
    }

    /**
     * Get previousChristmasFoodYes
     *
     * @return boolean
     */
    public function getPreviousChristmasFoodYes()
    {
        return $this->previousChristmasFoodYes;
    }

    /**
     * Set previousChristmasFoodNo
     *
     * @param boolean $previousChristmasFoodNo
     *
     * @return Client
     */
    public function setPreviousChristmasFoodNo($previousChristmasFoodNo)
    {
        $this->previousChristmasFoodNo = $previousChristmasFoodNo;

        return $this;
    }

    /**
     * Get previousChristmasFoodNo
     *
     * @return boolean
     */
    public function getPreviousChristmasFoodNo()
    {
        return $this->previousChristmasFoodNo;
    }

    /**
     * Set coatOrderDate
     *
     * @param \DateTime $coatOrderDate
     *
     * @return Client
     */
    public function setCoatOrderDate($coatOrderDate)
    {
        $this->coatOrderDate = $coatOrderDate;

        return $this;
    }

    /**
     * Get coatOrderDate
     *
     * @return \DateTime
     */
    public function getCoatOrderDate()
    {
        return $this->coatOrderDate;
    }

    /**
     * Set childrenNumber
     *
     * @param integer $childrenNumber
     *
     * @return Client
     */
    public function setChildrenNumber($childrenNumber)
    {
        $this->childrenNumber = $childrenNumber;

        return $this;
    }

    /**
     * Get childrenNumber
     *
     * @return integer
     */
    public function getChildrenNumber()
    {
        return $this->childrenNumber;
    }

    /**
     * Set childcareServices
     *
     * @param boolean $childcareServices
     *
     * @return Client
     */
    public function setChildcareServices($childcareServices)
    {
        $this->childcareServices = $childcareServices;

        return $this;
    }

    /**
     * Get childcareServices
     *
     * @return boolean
     */
    public function getChildcareServices()
    {
        return $this->childcareServices;
    }

    /**
     * Set heatShutoff
     *
     * @param boolean $heatShutoff
     *
     * @return Client
     */
    public function setHeatShutoff($heatShutoff)
    {
        $this->heatShutoff = $heatShutoff;

        return $this;
    }

    /**
     * Get heatShutoff
     *
     * @return boolean
     */
    public function getHeatShutoff()
    {
        return $this->heatShutoff;
    }

    /**
     * Set lightShutoff
     *
     * @param boolean $lightShutoff
     *
     * @return Client
     */
    public function setLightShutoff($lightShutoff)
    {
        $this->lightShutoff = $lightShutoff;

        return $this;
    }

    /**
     * Get lightShutoff
     *
     * @return boolean
     */
    public function getLightShutoff()
    {
        return $this->lightShutoff;
    }

    /**
     * Set waterShutoff
     *
     * @param boolean $waterShutoff
     *
     * @return Client
     */
    public function setWaterShutoff($waterShutoff)
    {
        $this->waterShutoff = $waterShutoff;

        return $this;
    }

    /**
     * Get waterShutoff
     *
     * @return boolean
     */
    public function getWaterShutoff()
    {
        return $this->waterShutoff;
    }

    /**
     * Set otherShutoff
     *
     * @param string $otherShutoff
     *
     * @return Client
     */
    public function setOtherShutoff($otherShutoff)
    {
        $this->otherShutoff = $otherShutoff;

        return $this;
    }

    /**
     * Get otherShutoff
     *
     * @return string
     */
    public function getOtherShutoff()
    {
        return $this->otherShutoff;
    }

    /**
     * Set taxesDifficulty
     *
     * @param boolean $taxesDifficulty
     *
     * @return Client
     */
    public function setTaxesDifficulty($taxesDifficulty)
    {
        $this->taxesDifficulty = $taxesDifficulty;

        return $this;
    }

    /**
     * Get taxesDifficulty
     *
     * @return boolean
     */
    public function getTaxesDifficulty()
    {
        return $this->taxesDifficulty;
    }

    /**
     * Set foreclosureNotice
     *
     * @param boolean $foreclosureNotice
     *
     * @return Client
     */
    public function setForeclosureNotice($foreclosureNotice)
    {
        $this->foreclosureNotice = $foreclosureNotice;

        return $this;
    }

    /**
     * Get foreclosureNotice
     *
     * @return boolean
     */
    public function getForeclosureNotice()
    {
        return $this->foreclosureNotice;
    }

    /**
     * Set landlordEviction
     *
     * @param boolean $landlordEviction
     *
     * @return Client
     */
    public function setLandlordEviction($landlordEviction)
    {
        $this->landlordEviction = $landlordEviction;

        return $this;
    }

    /**
     * Get landlordEviction
     *
     * @return boolean
     */
    public function getLandlordEviction()
    {
        return $this->landlordEviction;
    }

    /**
     * Set otherHousingIssue
     *
     * @param string $otherHousingIssue
     *
     * @return Client
     */
    public function setOtherHousingIssue($otherHousingIssue)
    {
        $this->otherHousingIssue = $otherHousingIssue;

        return $this;
    }

    /**
     * Get otherHousingIssue
     *
     * @return string
     */
    public function getOtherHousingIssue()
    {
        return $this->otherHousingIssue;
    }

}
