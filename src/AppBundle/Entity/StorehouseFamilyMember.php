<?php

	// src/AppBundle/Entity/StorehouseFamilyMember.php
	namespace AppBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;
	use Doctrine\Common\Collections\ArrayCollection;
	use Symfony\Component\Validator\Constraints as Assert;
	
	/**
	 * @ORM\Entity
	 * @ORM\Table(name="StorehouseFamilyMember")
	 */
	 
	class StorehouseFamilyMember
	{	
		/**
		 * @ORM\Column(type="integer")
		 * @ORM\Id
		 * @ORM\GeneratedValue(strategy="AUTO")
		*/
		protected $id;
		
		/**
		 * @ORM\Column(type="string", length=50, nullable=true)
		 */
		 protected $name;

		/**
		* @ORM\ManyToOne(targetEntity="StorehouseClient", inversedBy="storehouseFamilyMembers")
		* @ORM\JoinColumn(name="storehouseClient_id", referencedColumnName="id")
		*/
		protected $storehouseClient;
		
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
		 * @ORM\Column(type="string", length=10, nullable=true)
		 */		
		protected $gender;
		
		/**
		 * @ORM\Column(type="string", length=50, nullable=true)
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
     * @return StorehouseFamilyMember
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
     * Set storehouseClient
     *
     * @param \AppBundle\Entity\StorehouseClient $storehouseClient
     *
     * @return StorehouseFamilyMember
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
     * Set age
     *
     * @param integer $age
     *
     * @return StorehouseFamilyMember
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
     * @return StorehouseFamilyMember
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
     * @return StorehouseFamilyMember
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
