<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StarBaseRepository")
 * @ORM\Table(name="starbases")
 * @JMS\ExclusionPolicy("all")
 */
class Starbase
{
    const STATE_UNANCHORED = 0,
          STATE_OFFLINE = 1,
          STATE_ONLINING = 2,
          STATE_REINFORCED = 3,
          STATE_ONLINE = 4;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose()
     */
    protected $id;

    /**
     * @ORM\Column(type="bigint")
     * @JMS\Expose()
     */
    protected $item_id;

    /**
     * @ORM\Column(type="bigint")
     * @JMS\Expose()
     */
    protected $type_id;

    /**
     * @ORM\Column(type="bigint")
     * @JMS\Expose()
     */
    protected $location_id;

    /**
     * @ORM\Column(type="bigint")
     * @JMS\Expose()
     */
    protected $moon_id;

    /**
     * @ORM\Column(type="smallint")
     * @JMS\Expose()
     */
    protected $state;

    /**
     * @ORM\Column(type="datetime")
     * @JMS\Expose()
     */
    protected $state_timestamp;

    /**
     * @ORM\Column(type="datetime")
     * @JMS\Expose()
     */
    protected $online_timestamp;

    /**
     * @ORM\Column(type="bigint")
     * @JMS\Expose()
     */
    protected $standing_owner_id;

    /**
     * @ORM\Column(type="array")
     * @JMS\Expose()
     */
    protected $general_settings;

    /**
     * @ORM\Column(type="array")
     * @JMS\Expose()
     */
    protected $combat_settings;

    /**
     * @ORM\Column(type="array")
     * @JMS\Expose()
     */
    protected $fuel;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Corporation", inversedBy="starbases")
     */
    protected $corporation;

    /**
     * @JMS\Expose()
     */
    protected $descriptors;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    public function setDescriptors(array $desc)
    {
        $this->descriptors = $desc;

        return $this;
    }

    public function getDescriptors()
    {
        return $this->descriptors;
    }

    public function __construct()
    {
        $this->created_at = new \DateTime();
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
     * Set item_id.
     *
     * @param int $itemId
     *
     * @return Starbase
     */
    public function setItemId($itemId)
    {
        $this->item_id = $itemId;

        return $this;
    }

    /**
     * Get item_id.
     *
     * @return int
     */
    public function getItemId()
    {
        return $this->item_id;
    }

    /**
     * Set type_id.
     *
     * @param int $typeId
     *
     * @return Starbase
     */
    public function setTypeId($typeId)
    {
        $this->type_id = $typeId;

        return $this;
    }

    /**
     * Get type_id.
     *
     * @return int
     */
    public function getTypeId()
    {
        return $this->type_id;
    }

    /**
     * Set moon_id.
     *
     * @param int $moonId
     *
     * @return Starbase
     */
    public function setMoonId($moonId)
    {
        $this->moon_id = $moonId;

        return $this;
    }

    /**
     * Get moon_id.
     *
     * @return int
     */
    public function getMoonId()
    {
        return $this->moon_id;
    }

    /**
     * Set state.
     *
     * @param int $state
     *
     * @return Starbase
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state.
     *
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set state_timestamp.
     *
     * @param \DateTime $stateTimestamp
     *
     * @return Starbase
     */
    public function setStateTimestamp($stateTimestamp)
    {
        $this->state_timestamp = $stateTimestamp;

        return $this;
    }

    /**
     * Get state_timestamp.
     *
     * @return \DateTime
     */
    public function getStateTimestamp()
    {
        return $this->state_timestamp;
    }

    /**
     * Set online_timestamp.
     *
     * @param \DateTime $onlineTimestamp
     *
     * @return Starbase
     */
    public function setOnlineTimestamp($onlineTimestamp)
    {
        $this->online_timestamp = $onlineTimestamp;

        return $this;
    }

    /**
     * Get online_timestamp.
     *
     * @return \DateTime
     */
    public function getOnlineTimestamp()
    {
        return $this->online_timestamp;
    }

    /**
     * Set standing_owner_id.
     *
     * @param int $standingOwnerId
     *
     * @return Starbase
     */
    public function setStandingOwnerId($standingOwnerId)
    {
        $this->standing_owner_id = $standingOwnerId;

        return $this;
    }

    /**
     * Get standing_owner_id.
     *
     * @return int
     */
    public function getStandingOwnerId()
    {
        return $this->standing_owner_id;
    }

    /**
     * Set created_at.
     *
     * @param \DateTime $createdAt
     *
     * @return Starbase
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
     * Set corporation.
     *
     * @param \AppBundle\Entity\Corporation $corporation
     *
     * @return Starbase
     */
    public function setCorporation(\AppBundle\Entity\Corporation $corporation = null)
    {
        $this->corporation = $corporation;

        return $this;
    }

    /**
     * Get corporation.
     *
     * @return \AppBundle\Entity\Corporation
     */
    public function getCorporation()
    {
        return $this->corporation;
    }

    /**
     * Set location_id.
     *
     * @param int $locationId
     *
     * @return Starbase
     */
    public function setLocationId($locationId)
    {
        $this->location_id = $locationId;

        return $this;
    }

    /**
     * Get location_id.
     *
     * @return int
     */
    public function getLocationId()
    {
        return $this->location_id;
    }

    /**
     * Set general_settings.
     *
     * @param array $generalSettings
     *
     * @return Starbase
     */
    public function setGeneralSettings($generalSettings)
    {
        $this->general_settings = $generalSettings;

        return $this;
    }

    /**
     * Get general_settings.
     *
     * @return array
     */
    public function getGeneralSettings()
    {
        return $this->general_settings;
    }

    /**
     * Set combat_settings.
     *
     * @param array $combatSettings
     *
     * @return Starbase
     */
    public function setCombatSettings($combatSettings)
    {
        $this->combat_settings = $combatSettings;

        return $this;
    }

    /**
     * Get combat_settings.
     *
     * @return array
     */
    public function getCombatSettings()
    {
        return $this->combat_settings;
    }

    /**
     * Set fuel.
     *
     * @param array $fuel
     *
     * @return Starbase
     */
    public function setFuel($fuel)
    {
        $this->fuel = $fuel;

        return $this;
    }

    /**
     * Get fuel.
     *
     * @return array
     */
    public function getFuel()
    {
        return $this->fuel;
    }
}
