<?php

	namespace AppBundle\Entity;
	
	use Doctrine\ORM\Mapping as ORM;
	use Doctrine\Common\Collections\ArrayCollection;
	use Symfony\Component\Validator\Constraints as Assert;
	
	/**
	 * @ORM\Entity
	 * @ORM\Table(name="WalkIn")
	 */
	class WalkIn
	{	
		/**
		 * @ORM\OneToMany(targetEntity="WalkInFamilyMember", mappedBy="walkIn", cascade={"persist"})
		 * @ORM\OrderBy({"age" = "DESC"})
		 */
		protected $walkInFamilyMembers;
		
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
		 */		
		protected $gender;

		protected $familyCount;	
		
	    /**
     * Constructor
     */
    public function __construct()
    {
        $this->walkInFamilyMembers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set age
     *
     * @param integer $age
     *
     * @return WalkIn
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
     * @return WalkIn
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
     * Set familyCount
     *
     * @param string $familyCount
     *
     * @return WalkIn
     */
    public function setFamilyCount($familyCount)
    {
        $this->familyCount = $familyCount;

        return $this;
    }

    /**
     * Get familyCount
     *
     * @return string
     */
    public function getFamilyCount()
    {
        return $this->familyCount;
    }

	public function getWalkInFamilyMembers()
    {
        return $this->walkInFamilyMembers;
    }
	
    public function addWalkInFamilyMember(WalkInFamilyMember $walkInFamilyMember)
    {
        $walkInFamilyMember->setWalkIn($this);
    
        $this->walkInFamilyMembers->add($walkInFamilyMember);
    }

    public function removeWalkInFamilyMember(WalkInFamilyMember $walkInFamilyMember)
    {
        $this->walkInFamilyMembers->removeElement($walkInFamilyMember);
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return WalkIn
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
}
