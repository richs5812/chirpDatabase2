<?php

	// src/AppBundle/Entity/FamilyMember.php
	namespace AppBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;
	use Doctrine\Common\Collections\ArrayCollection;
	use Symfony\Component\Validator\Constraints as Assert;
	
	/**
	 * @ORM\Entity
	 * @ORM\Table(name="FamilyMember")
	 */
	 
	class FamilyMember
	{	
		/**
		 * @ORM\Column(type="integer")
		 * @ORM\Id
		 * @ORM\GeneratedValue(strategy="AUTO")
		*/
		protected $id;
		
		/**
		 * @ORM\Column(type="string", length=255, nullable=true)
		 */
		 protected $name;

		/**
		* @ORM\ManyToOne(targetEntity="Client", inversedBy="familyMembers")
		* @ORM\JoinColumn(name="client_id", referencedColumnName="id")
		*/
		protected $client;
		
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
		
		/**
		 * @ORM\Column(type="string", length=255, nullable=true)
		 */
		protected $relationship;

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
     * Set name
     *
     * @param string $name
     *
     * @return FamilyMember
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return FamilyMember
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
     * Set age
     *
     * @param integer $age
     *
     * @return FamilyMember
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
     * @return FamilyMember
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
     * Set relationship
     *
     * @param string $relationship
     *
     * @return FamilyMember
     */
    public function setRelationship($relationship)
    {
        $this->relationship = $relationship;

        return $this;
    }

    /**
     * Get relationship
     *
     * @return string
     */
    public function getRelationship()
    {
        return $this->relationship;
    }
}
