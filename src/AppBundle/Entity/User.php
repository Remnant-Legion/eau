<?php

namespace AppBundle\Entity;

use AppBundle\Validator\Constraints\DuplicateEmailConstraint;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="users")
 * @JMS\ExclusionPolicy("all")
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 *
 * Class User
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose()
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     * @JMS\Expose()
     */
    protected $created_at;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Character", mappedBy="user", cascade={"persist"})
     * @JMS\Expose()
     */
    protected $characters;

    /**
     * @ORM\Column(type="datetime")
     * @JMS\Expose()
     */
    protected $updated_at;

    /**
     * @ORM\Column(type="datetime", nullable=true, )
     * @JMS\Expose()
     */
    protected $deleted_at;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('username', [new Assert\NotBlank()])
            ->addPropertyConstraints('email', [
                new Assert\Email(),
                new Assert\NotBlank(),
                new DuplicateEmailConstraint(['groups' => 'new']),
            ])
            ->addPropertyConstraints('plainPassword', [
                new Assert\NotBlank(['groups' => 'new']),
            ])
            ->addPropertyConstraints('roles', [
                new Assert\NotBlank(),
            ]);
    }

    public function __construct()
    {
        parent::__construct();
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
        $this->characters = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set created_at.
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set deleted_at.
     *
     * @param \DateTime $deletedAt
     *
     * @return User
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deleted_at = $deletedAt;

        return $this;
    }

    /**
     * Get deleted_at.
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }

    /**
     * Set updated_at.
     *
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updated_at.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Add characters.
     *
     * @param \AppBundle\Entity\Character $characters
     *
     * @return User
     */
    public function addCharacter(\AppBundle\Entity\Character $characters)
    {
        if (!$this->characters->contains($characters)) {
            $this->characters[] = $characters;
            $characters->setUser($this);
        }

        return $this;
    }

    /**
     * Remove characters.
     *
     * @param \AppBundle\Entity\Character $characters
     */
    public function removeCharacter(\AppBundle\Entity\Character $characters)
    {
        $this->characters->removeElement($characters);
    }

    /**
     * Get characters.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCharacters()
    {
        return $this->characters;
    }
}
