<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AccountBalanceRepository")
 * @ORM\Table(name="account_balances")
 * @JMS\ExclusionPolicy("all")
 */
class AccountBalance
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose()
     */
    protected $id;

    /**
     * @ORM\Column(type="decimal", precision=16, scale=2)
     */
    protected $balance;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Account", inversedBy="balances")
     */
    protected $account;

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
     * Set balance.
     *
     * @param string $balance
     *
     * @return AccountBalance
     */
    public function setBalance($balance)
    {
        $this->balance = floatval($balance);

        return $this;
    }

    /**
     * Get balance.
     *
     * @return string
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set created_at.
     *
     * @param \DateTime $createdAt
     *
     * @return AccountBalance
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
     * @return AccountBalance
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
}
