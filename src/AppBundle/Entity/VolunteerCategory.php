<?php

	namespace AppBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;
	use Doctrine\Common\Collections\ArrayCollection;
	use Symfony\Component\Validator\Constraints as Assert;
	
	/**
	 * @ORM\Entity
	 * @ORM\Table(name="VolunteerCategory")
	 */
	class VolunteerCategory
	{	
		/**
		 * @ORM\Column(type="integer")
		 * @ORM\Id
		 * @ORM\GeneratedValue(strategy="AUTO")
		*/
		protected $id;
		
		/**
		 * @ORM\Column(type="string", length=200)
		 */
		 protected $category;


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
     * Set category
     *
     * @param string $category
     *
     * @return VolunteerCategory
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }
}
