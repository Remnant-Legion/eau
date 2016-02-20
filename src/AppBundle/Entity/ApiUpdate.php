<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ApiUpdateRepository")
 * @ORM\Table(name="api_updates")
 * @ORM\HasLifecycleCallbacks()
 * @JMS\ExclusionPolicy("all")
 */
class ApiUpdate
{
    const CACHE_STYLE_SHORT = 1,
          CACHE_STYLE_LONG = 2;

    const CORP_ACC_BALANCES = 1,
          CORP_ASSET_LIST = 2,
          CORP_CONTACT_LIST = 3,
          CORP_CONTAINER_LOG = 4,
          CORP_CONTRACTS = 5,
          CORP_MARKET_ORDERS = 6,
          CORP_WALLET_JOURNAL = 7,
          CORP_WALLET_TRANSACTION = 8,
          CORP_STARBASE_LIST = 9,
          CORP_DETAILS = 10,
          CORP_MEMBERS = 11,
          CORP_TITLES = 12;

    const REF_TYPES = 99,
          CONQUERABLE_STATIONS = 99;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose()
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     * @JMS\Expose()
     */
    protected $type;

    /**
     * @ORM\Column(type="integer")
     */
    protected $api_call;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Corporation", inversedBy="api_updates")
     */
    protected $corporation;

    /**
     * @ORM\Column(type="boolean")
     * @JMS\Expose()
     */
    protected $succeeded;

    /**
     * @ORM\Column(type="datetime")
     * @JMS\Expose()
     */
    protected $created_at;

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
     * Set type.
     *
     * @param int $type
     *
     * @return ApiUpdate
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set created_at.
     *
     * @param \DateTime $createdAt
     *
     * @return ApiUpdate
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
     * @return ApiUpdate
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
     * Set api_call.
     *
     * @param int $apiCall
     *
     * @return ApiUpdate
     */
    public function setApiCall($apiCall)
    {
        $this->api_call = $apiCall;

        return $this;
    }

    /**
     * Get api_call.
     *
     * @return int
     */
    public function getApiCall()
    {
        return $this->api_call;
    }

    /**
     * Set succeeded.
     *
     * @param bool $succeeded
     *
     * @return ApiUpdate
     */
    public function setSucceeded($succeeded)
    {
        $this->succeeded = $succeeded;

        return $this;
    }

    /**
     * Get succeeded.
     *
     * @return bool
     */
    public function getSucceeded()
    {
        return $this->succeeded;
    }
}
