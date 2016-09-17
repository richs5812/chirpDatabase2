<?php

	namespace AppBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;
	use Doctrine\Common\Collections\ArrayCollection;
	use Symfony\Component\Validator\Constraints as Assert;
	
	/**
	 * @ORM\Entity
	 * @ORM\Table(name="ReferralName")
	 */
	 
	class ReferralName
	{	
		/**
		 * @ORM\Column(type="integer")
		 * @ORM\Id
		 * @ORM\GeneratedValue(strategy="AUTO")
		*/
		protected $id;
		
		/**
		 * @ORM\Column(type="string", length=255, nullable=false)
		 */
		 protected $name;

		/**
		 * @ORM\OneToMany(targetEntity="Referral", mappedBy="referralName", cascade={"remove"})
		 */
		protected $referrals;
		
		protected $count;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->referrals = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return ReferralName
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

    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    public function getCount()
    {
        return $this->count;
    }
    
    /**
     * Add referral
     *
     * @param \AppBundle\Entity\Referral $referral
     *
     * @return ReferralName
     */
    public function addReferral(\AppBundle\Entity\Referral $referral)
    {
        $this->referrals[] = $referral;

        return $this;
    }

    /**
     * Remove referral
     *
     * @param \AppBundle\Entity\Referral $referral
     */
    public function removeReferral(\AppBundle\Entity\Referral $referral)
    {
        $this->referrals->removeElement($referral);
    }

    /**
     * Get referrals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReferrals()
    {
        return $this->referrals;
    }
}
