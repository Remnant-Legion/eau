<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CorporationRepository")
 * @ORM\Table(name="corporations", uniqueConstraints={
 @ORM\UniqueConstraint(name="eve_id_idx", columns={"eve_id"})
 * })
 * @ORM\HasLifecycleCallbacks()
 * @JMS\ExclusionPolicy("all")
 */
class Corporation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose()
     */
    protected $id;

    /**
     * @JMS\Expose()
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\CorporationDetail", mappedBy="corporation", cascade={"persist"})
     */
    protected $corporation_details;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @JMS\Expose()
     */
    protected $eve_id;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Account", mappedBy="corporation", cascade={"persist"})
     */
    protected $accounts;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Starbase", mappedBy="corporation", cascade={"persist"})
     */
    protected $starbases;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MarketOrderGroup", mappedBy="corporation", cascade={"persist"})
     */
    protected $market_order_groups;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ApiCredentials", mappedBy="corporation", cascade={"persist"})
     */
    protected $api_credentials;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ApiUpdate", mappedBy="corporation", cascade={"persist"})
     */
    protected $api_updates;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CorporationTitle", mappedBy="corporation", cascade={"persist"})
     */
    protected $titles;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AssetGroup", mappedBy="corporation", cascade={"persist"})
     */
    protected $asset_groups;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CorporationMember", mappedBy="corporation", cascade={"persist"})
     */
    protected $corporation_members;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BuybackConfiguration", mappedBy="corporation", cascade={"persist"})
     */
    protected $buyback_configurations;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    protected $created_by;

    /**
     * @ORM\Column(type="datetime")
     * @JMS\Expose()
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deleted_at;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('api_credentials', [
            new Assert\Valid(),
        ]);
    }

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->accounts = new ArrayCollection();
        $this->api_updates = new ArrayCollection();
        $this->api_credentials = new ArrayCollection();
        $this->starbases = new ArrayCollection();
        $this->asset_groups = new ArrayCollection();
        $this->buyback_configurations = new ArrayCollection();
        $this->market_order_groups = new ArrayCollection();
        $this->corporation_members = new ArrayCollection();
        $this->titles = new ArrayCollection();
    }

    /**
     * Add accounts.
     *
     * @param \AppBundle\Entity\Account $accounts
     *
     * @return Corporation
     */
    public function addAccount(\AppBundle\Entity\Account $accounts)
    {
        if (!$this->accounts->contains($accounts)) {
            $this->accounts[] = $accounts;
            $accounts->setCorporation($this);
        }

        return $this;
    }

    /**
     * Add assets.
     *
     * @param \AppBundle\Entity\Asset $assets
     *
     * @return Corporation
     */
    public function addAssetGroup(\AppBundle\Entity\AssetGroup $assets)
    {
        if (!$this->asset_groups->contains($assets)) {
            $this->asset_groups[] = $assets;
            $assets->setCorporation($this);
        }

        return $this;
    }

    public function addStarbase(Starbase $starbase)
    {
        if (!$this->starbases->contains($starbase)) {
            $this->starbases[] = $starbase;
            $starbase->setCorporation($this);
        }

        return $this;
    }

    /**
     * Add market_orders.
     *
     * @param \AppBundle\Entity\MarketOrder $marketOrders
     *
     * @return Corporation
     */
    public function addMarketOrderGroup(\AppBundle\Entity\MarketOrderGroup $marketOrders)
    {
        if (!$this->market_order_groups->contains($marketOrders)) {
            $this->market_order_groups[] = $marketOrders;
            $marketOrders->setCorporation($this);
        }

        return $this;
    }

    /**
     * Add api_updates.
     *
     * @param \AppBundle\Entity\ApiUpdate $apiUpdates
     *
     * @return Corporation
     */
    public function addApiUpdate(\AppBundle\Entity\ApiUpdate $apiUpdates)
    {
        if (!$this->api_updates->contains($apiUpdates)) {
            $this->api_updates[] = $apiUpdates;
            $apiUpdates->setCorporation($this);
        }

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
     * Set created_at.
     *
     * @param \DateTime $createdAt
     *
     * @return Corporation
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
     * @return Corporation
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
     * Set created_by.
     *
     * @param \AppBundle\Entity\User $createdBy
     *
     * @return Corporation
     */
    public function setCreatedBy(\AppBundle\Entity\User $createdBy = null)
    {
        $this->created_by = $createdBy;

        return $this;
    }

    /**
     * Get created_by.
     *
     * @return \AppBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Set eve_id.
     *
     * @param int $eveId
     *
     * @return Corporation
     */
    public function setEveId($eveId)
    {
        $this->eve_id = $eveId;

        return $this;
    }

    /**
     * Get eve_id.
     *
     * @return int
     */
    public function getEveId()
    {
        return $this->eve_id;
    }

    /**
     * Remove accounts.
     *
     * @param \AppBundle\Entity\Account $accounts
     */
    public function removeAccount(\AppBundle\Entity\Account $accounts)
    {
        $this->accounts->removeElement($accounts);
    }

    /**
     * Get accounts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Set lasted_updated_at.
     *
     * @param \DateTime $lastedUpdatedAt
     *
     * @return Corporation
     */
    public function setLastUpdatedAt($lastedUpdatedAt)
    {
        $this->last_updated_at = $lastedUpdatedAt;

        return $this;
    }

    /**
     * Get lasted_updated_at.
     *
     * @return \DateTime
     */
    public function getLastUpdatedAt()
    {
        return $this->last_updated_at;
    }

    /**
     * Remove asset_groupings.
     *
     * @param \AppBundle\Entity\AssetGrouping $assetGroupings
     */
    public function removeAssetGroup(\AppBundle\Entity\AssetGroup $assetGroupings)
    {
        $this->asset_groups->removeElement($assetGroupings);
    }

    /**
     * Get asset_groupings.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssetGroups()
    {
        return $this->asset_groups;
    }

    /**
     * Remove api_updates.
     *
     * @param \AppBundle\Entity\ApiUpdate $apiUpdates
     */
    public function removeApiUpdate(\AppBundle\Entity\ApiUpdate $apiUpdates)
    {
        $this->api_updates->removeElement($apiUpdates);
    }

    /**
     * Get api_updates.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApiUpdates()
    {
        return $this->api_updates;
    }

    /**
     * Add api_credentials.
     *
     * @param \AppBundle\Entity\ApiCredentials $apiCredentials
     *
     * @return Corporation
     */
    public function addApiCredential(\AppBundle\Entity\ApiCredentials $apiCredentials)
    {
        if (!$this->api_credentials->contains($apiCredentials)) {
            $this->api_credentials[] = $apiCredentials;
            $apiCredentials->setCorporation($this);
        }

        return $this;
    }

    /**
     * Remove api_credentials.
     *
     * @param \AppBundle\Entity\ApiCredentials $apiCredentials
     */
    public function removeApiCredential(\AppBundle\Entity\ApiCredentials $apiCredentials)
    {
        $this->api_credentials->removeElement($apiCredentials);
    }

    /**
     * Get api_credentials.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApiCredentials()
    {
        return $this->api_credentials;
    }

    /**
     * Set corporation_details.
     *
     * @param \AppBundle\Entity\CorporationDetail $corporationDetails
     *
     * @return Corporation
     */
    public function setCorporationDetails(\AppBundle\Entity\CorporationDetail $corporationDetails = null)
    {
        $this->corporation_details = $corporationDetails;
        $corporationDetails->setCorporation($this);

        return $this;
    }

    /**
     * Get corporation_details.
     *
     * @return \AppBundle\Entity\CorporationDetail
     */
    public function getCorporationDetails()
    {
        return $this->corporation_details;
    }

    /**
     * Remove market_order_groups.
     *
     * @param \AppBundle\Entity\MarketOrderGroup $marketOrderGroups
     */
    public function removeMarketOrderGroup(\AppBundle\Entity\MarketOrderGroup $marketOrderGroups)
    {
        $this->market_order_groups->removeElement($marketOrderGroups);
    }

    /**
     * Get market_order_groups.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMarketOrderGroups()
    {
        return $this->market_order_groups;
    }

    /**
     * Remove starbases.
     *
     * @param \AppBundle\Entity\Starbase $starbases
     */
    public function removeStarbase(\AppBundle\Entity\Starbase $starbases)
    {
        $this->starbases->removeElement($starbases);
    }

    /**
     * Get starbases.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStarbases()
    {
        return $this->starbases;
    }

    /**
     * Add corporation_members.
     *
     * @param \AppBundle\Entity\CorporationMember $corporationMembers
     *
     * @return Corporation
     */
    public function addCorporationMember(\AppBundle\Entity\CorporationMember $corporationMembers)
    {
        if (!$this->corporation_members->contains($corporationMembers)) {
            $this->corporation_members[] = $corporationMembers;
            $corporationMembers->setCorporation($this);
        }

        return $this;
    }

    /**
     * Remove corporation_members.
     *
     * @param \AppBundle\Entity\CorporationMember $corporationMembers
     */
    public function removeCorporationMember(\AppBundle\Entity\CorporationMember $corporationMembers)
    {
        $this->corporation_members->removeElement($corporationMembers);
    }

    /**
     * Get corporation_members.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCorporationMembers()
    {
        return $this->corporation_members;
    }

    /**
     * Add buyback_configurations.
     *
     * @param \AppBundle\Entity\BuybackConfiguration $buybackConfigurations
     *
     * @return Corporation
     */
    public function addBuybackConfiguration(\AppBundle\Entity\BuybackConfiguration $buybackConfigurations)
    {
        if (!$this->buyback_configurations->contains(($buybackConfigurations))){
            $this->buyback_configurations[] = $buybackConfigurations;
            $buybackConfigurations->setCorporation($this);
        }

        return $this;
    }

    /**
     * Remove buyback_configurations.
     *
     * @param \AppBundle\Entity\BuybackConfiguration $buybackConfigurations
     */
    public function removeBuybackConfiguration(\AppBundle\Entity\BuybackConfiguration $buybackConfigurations)
    {
        $this->buyback_configurations->removeElement($buybackConfigurations);
    }

    /**
     * Get buyback_configurations.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBuybackConfigurations()
    {
        return $this->buyback_configurations;
    }

    /**
     * Add titles.
     *
     * @param \AppBundle\Entity\CorporationTitle $titles
     *
     * @return Corporation
     */
    public function addTitle(\AppBundle\Entity\CorporationTitle $titles)
    {
        if (!$this->titles->contains($titles)) {
            $this->titles[] = $titles;
            $titles->setCorporation($this);
        }

        return $this;
    }

    /**
     * Remove titles.
     *
     * @param \AppBundle\Entity\CorporationTitle $titles
     */
    public function removeTitle(\AppBundle\Entity\CorporationTitle $titles)
    {
        $this->titles->removeElement($titles);
    }

    /**
     * Get titles.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTitles()
    {
        return $this->titles;
    }
}
