<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MarketTransactionRepository")
 * @ORM\Table(name="market_transactions", uniqueConstraints={
 @ORM\UniqueConstraint(name="date_transID_jTransID_acc_idx", columns={"date", "transaction_id", "account_id", "journal_transaction_id"}),
 * })
 * @JMS\ExclusionPolicy("all")
 */
class MarketTransaction
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
    protected $date;

    /**
     * @ORM\Column(type="bigint")
     */
    protected $transaction_id;

    /**
     * @ORM\Column(type="bigint")
     * @JMS\Expose()
     */
    protected $quantity;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     */
    protected $item_name;

    /**
     * @ORM\Column(type="integer")
     * @JMS\Expose()
     */
    protected $item_id;

    /**
     * @ORM\Column(type="decimal", precision=16, scale=2)
     * @JMS\Expose()
     */
    protected $price;

    /**
     * @ORM\Column(type="integer")
     * @JMS\Expose()
     */
    protected $client_id;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     */
    protected $client_name;

    /**
     * @ORM\Column(type="integer")
     * @JMS\Expose()
     */
    protected $character_id;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     */
    protected $character_name;

    /**
     * @ORM\Column(type="integer")
     * @JMS\Expose()
     */
    protected $station_id;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     */
    protected $station_name;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     */
    protected $transaction_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $transaction_for;

    /**
     * @ORM\Column(type="bigint")
     */
    protected $journal_transaction_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $client_type_id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Account", inversedBy="market_transactions")
     */
    protected $account;

    /**
     * @ORM\Column(type="datetime")
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
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return MarketTransaction
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set transaction_id.
     *
     * @param int $transactionId
     *
     * @return MarketTransaction
     */
    public function setTransactionId($transactionId)
    {
        $this->transaction_id = intval($transactionId);

        return $this;
    }

    /**
     * Get transaction_id.
     *
     * @return int
     */
    public function getTransactionId()
    {
        return $this->transaction_id;
    }

    /**
     * Set quantity.
     *
     * @param int $quantity
     *
     * @return MarketTransaction
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
     * Set item_name.
     *
     * @param string $itemName
     *
     * @return MarketTransaction
     */
    public function setItemName($itemName)
    {
        $this->item_name = $itemName;

        return $this;
    }

    /**
     * Get item_name.
     *
     * @return string
     */
    public function getItemName()
    {
        return $this->item_name;
    }

    /**
     * Set item_id.
     *
     * @param int $itemId
     *
     * @return MarketTransaction
     */
    public function setItemId($itemId)
    {
        $this->item_id = intval($itemId);

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
     * Set price.
     *
     * @param string $price
     *
     * @return MarketTransaction
     */
    public function setPrice($price)
    {
        $this->price = floatval($price);

        return $this;
    }

    /**
     * Get price.
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set client_id.
     *
     * @param int $clientId
     *
     * @return MarketTransaction
     */
    public function setClientId($clientId)
    {
        $this->client_id = intval($clientId);

        return $this;
    }

    /**
     * Get client_id.
     *
     * @return int
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * Set client_name.
     *
     * @param string $clientName
     *
     * @return MarketTransaction
     */
    public function setClientName($clientName)
    {
        $this->client_name = $clientName;

        return $this;
    }

    /**
     * Get client_name.
     *
     * @return string
     */
    public function getClientName()
    {
        return $this->client_name;
    }

    /**
     * Set station_id.
     *
     * @param int $stationId
     *
     * @return MarketTransaction
     */
    public function setStationId($stationId)
    {
        $this->station_id = intval($stationId);

        return $this;
    }

    /**
     * Get station_id.
     *
     * @return int
     */
    public function getStationId()
    {
        return $this->station_id;
    }

    /**
     * Set station_name.
     *
     * @param string $stationName
     *
     * @return MarketTransaction
     */
    public function setStationName($stationName)
    {
        $this->station_name = $stationName;

        return $this;
    }

    /**
     * Get station_name.
     *
     * @return string
     */
    public function getStationName()
    {
        return $this->station_name;
    }

    /**
     * Set transaction_type.
     *
     * @param string $transactionType
     *
     * @return MarketTransaction
     */
    public function setTransactionType($transactionType)
    {
        $this->transaction_type = $transactionType;

        return $this;
    }

    /**
     * Get transaction_type.
     *
     * @return string
     */
    public function getTransactionType()
    {
        return $this->transaction_type;
    }

    /**
     * Set transaction_for.
     *
     * @param string $transactionFor
     *
     * @return MarketTransaction
     */
    public function setTransactionFor($transactionFor)
    {
        $this->transaction_for = $transactionFor;

        return $this;
    }

    /**
     * Get transaction_for.
     *
     * @return string
     */
    public function getTransactionFor()
    {
        return $this->transaction_for;
    }

    /**
     * Set journal_transaction_id.
     *
     * @param int $journalTransactionId
     *
     * @return MarketTransaction
     */
    public function setJournalTransactionId($journalTransactionId)
    {
        $this->journal_transaction_id = intval($journalTransactionId);

        return $this;
    }

    /**
     * Get journal_transaction_id.
     *
     * @return int
     */
    public function getJournalTransactionId()
    {
        return $this->journal_transaction_id;
    }

    /**
     * Set created_at.
     *
     * @param \DateTime $createdAt
     *
     * @return MarketTransaction
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
     * Set account.
     *
     * @param \AppBundle\Entity\Account $account
     *
     * @return MarketTransaction
     */
    public function setAccount(\AppBundle\Entity\Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account.
     *
     * @return \AppBundle\Entity\Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set character_id.
     *
     * @param int $characterId
     *
     * @return MarketTransaction
     */
    public function setCharacterId($characterId)
    {
        $this->character_id = $characterId;

        return $this;
    }

    /**
     * Get character_id.
     *
     * @return int
     */
    public function getCharacterId()
    {
        return $this->character_id;
    }

    /**
     * Set character_name.
     *
     * @param string $characterName
     *
     * @return MarketTransaction
     */
    public function setCharacterName($characterName)
    {
        $this->character_name = $characterName;

        return $this;
    }

    /**
     * Get character_name.
     *
     * @return string
     */
    public function getCharacterName()
    {
        return $this->character_name;
    }

    /**
     * Set client_type_id.
     *
     * @param int $clientTypeId
     *
     * @return MarketTransaction
     */
    public function setClientTypeId($clientTypeId)
    {
        $this->client_type_id = $clientTypeId;

        return $this;
    }

    /**
     * Get client_type_id.
     *
     * @return int
     */
    public function getClientTypeId()
    {
        return $this->client_type_id;
    }
}
