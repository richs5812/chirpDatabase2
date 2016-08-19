<?php

	// src/AppBundle/Entity/Poundage.php
	namespace AppBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;
	use Doctrine\Common\Collections\ArrayCollection;
	use Symfony\Component\Validator\Constraints as Assert;
	
	/**
	 * @ORM\Entity
	 * @ORM\Table(name="Poundage")
	 */
	 
	class Poundage
	{	
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
		 */			
		protected $poundage;

		/**
		 * @ORM\Column(type="text", nullable=true)
		 */		
		protected $note;
		
	
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
     * @return Poundage
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
     * Set poundage
     *
     * @param integer $poundage
     *
     * @return Poundage
     */
    public function setPoundage($poundage)
    {
        $this->poundage = $poundage;

        return $this;
    }

    /**
     * Get poundage
     *
     * @return integer
     */
    public function getPoundage()
    {
        return $this->poundage;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Poundage
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
}
