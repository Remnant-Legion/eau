<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AssetRepository")
 * @ORM\Table(name="assets")
 * @JMS\ExclusionPolicy("all")
 */
class Asset
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose()
     */
    protected $id;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     * @JMS\Expose()
     */
    protected $itemId;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     * @JMS\Expose()
     */
    protected $locationId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @JMS\Expose()
     */
    protected $typeId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @JMS\Expose()
     */
    protected $quantity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @JMS\Expose()
     */
    protected $flag_id;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Asset", mappedBy="parent", cascade={"persist"})
     * @JMS\Expose()
     */
    protected $contents;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Asset", inversedBy="contents")
     */
    protected $parent;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $singleton;

    /**
     * @JMS\Expose()
     * @ORM\Column(type="array", nullable=true)
     */
    protected $descriptors;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\AssetGroup", inversedBy="assets")
     */
    protected $asset_group;

    public function __construct()
    {
        $this->contents = new ArrayCollection();
    }

    public function getDescriptors()
    {
        return $this->descriptors === null ? [] :  $this->descriptors;
    }

    public function setDescriptors(array $desc)
    {
        $this->descriptors = $desc;

        return $this;
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
     * Set itemId.
     *
     * @param int $itemId
     *
     * @return Asset
     */
    public function setItemId($itemId)
    {
        $this->itemId = intval($itemId);

        return $this;
    }

    /**
     * Get itemId.
     *
     * @return int
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * Set locationId.
     *
     * @param int $locationId
     *
     * @return Asset
     */
    public function setLocationId($locationId)
    {
        $this->locationId = intval($locationId);

        return $this;
    }

    /**
     * Get locationId.
     *
     * @return int
     */
    public function getLocationId()
    {
        return $this->locationId;
    }

    /**
     * Set typeId.
     *
     * @param int $typeId
     *
     * @return Asset
     */
    public function setTypeId($typeId)
    {
        $this->typeId = intval($typeId);

        return $this;
    }

    /**
     * Get typeId.
     *
     * @return int
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Set quantity.
     *
     * @param int $quantity
     *
     * @return Asset
     */
    public function setQuantity($quantity)
    {
        $this->quantity = intval($quantity);

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set flag.
     *
     * @param int $flag
     *
     * @return Asset
     */
    public function setFlagId($flag)
    {
        $this->flag_id = intval($flag);

        return $this;
    }

    /**
     * Get flag.
     *
     * @return int
     */
    public function getFlagId()
    {
        return $this->flag_id;
    }

    /**
     * Set singleton.
     *
     * @param int $singleton
     *
     * @return Asset
     */
    public function setSingleton($singleton)
    {
        $this->singleton = (bool) $singleton;

        return $this;
    }

    /**
     * Get singleton.
     *
     * @return int
     */
    public function getSingleton()
    {
        return $this->singleton;
    }

    /**
     * Set asset_group.
     *
     * @param \AppBundle\Entity\AssetGroup $assetGroup
     *
     * @return Asset
     */
    public function setAssetGroup(\AppBundle\Entity\AssetGroup $assetGroup = null)
    {
        $this->asset_group = $assetGroup;

        return $this;
    }

    /**
     * Get asset_group.
     *
     * @return \AppBundle\Entity\AssetGroup
     */
    public function getAssetGroup()
    {
        return $this->asset_group;
    }

    /**
     * Add contents.
     *
     * @param \AppBundle\Entity\Asset $contents
     *
     * @return Asset
     */
    public function addContent(\AppBundle\Entity\Asset $contents)
    {
        if (!$this->contents->contains($contents)) {
            $this->contents[] = $contents;
            $contents->setParent($this);
        }

        return $this;
    }

    /**
     * Remove contents.
     *
     * @param \AppBundle\Entity\Asset $contents
     */
    public function removeContent(\AppBundle\Entity\Asset $contents)
    {
        $this->contents->removeElement($contents);
    }

    /**
     * Get contents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * Set parent.
     *
     * @param \AppBundle\Entity\Asset $parent
     *
     * @return Asset
     */
    public function setParent(\AppBundle\Entity\Asset $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return \AppBundle\Entity\Asset
     */
    public function getParent()
    {
        return $this->parent;
    }
}
