<?php

	namespace AppBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;
	use Doctrine\Common\Collections\ArrayCollection;
	use Symfony\Component\Validator\Constraints as Assert;
	
	/**
	 * @ORM\Entity
	 * @ORM\Table(name="WalkInFamilyMember")
	 */
	 
	class WalkInFamilyMember
	{	
		/**
		 * @ORM\Column(type="integer")
		 * @ORM\Id
		 * @ORM\GeneratedValue(strategy="AUTO")
		*/
		protected $id;
		
		/**
		* @ORM\ManyToOne(targetEntity="WalkIn", inversedBy="walkInFamilyMembers")
		* @ORM\JoinColumn(name="walkIn_id", referencedColumnName="id")
		*/
		protected $walkIn;
		
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
		 * @return WalkInFamilyMember
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
		 * @return WalkInFamilyMember
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
		 * Set walkIn
		 *
		 * @param \AppBundle\Entity\WalkIn $walkIn
		 *
		 * @return WalkInFamilyMember
		 */
		public function setWalkIn(\AppBundle\Entity\WalkIn $walkIn = null)
		{
			$this->walkIn = $walkIn;

			return $this;
		}

		/**
		 * Get walkIn
		 *
		 * @return \AppBundle\Entity\WalkIn
		 */
		public function getWalkIn()
		{
			return $this->walkIn;
		}
}
