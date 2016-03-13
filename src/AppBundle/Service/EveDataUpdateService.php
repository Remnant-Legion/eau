<?php

namespace AppBundle\Service;

use AppBundle\Entity\ApiCredentials;
use AppBundle\Entity\ApiUpdate;
use AppBundle\Entity\Corporation;
use AppBundle\Service\DataManager\Corporation\AssetManager;
use AppBundle\Service\DataManager\Corporation\AccountManager;
use AppBundle\Service\DataManager\Corporation\CorporationManager;
use AppBundle\Service\DataManager\Corporation\JournalTransactionManager;
use AppBundle\Service\DataManager\Corporation\MarketOrderManager;
use AppBundle\Service\DataManager\Corporation\MarketTransactionManager;
use AppBundle\Service\DataManager\Corporation\StarbaseManager;
use AppBundle\Service\DataManager\Corporation\TitleManager;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Monolog\Logger;

class EveDataUpdateService
{
    protected $doctrine;

    protected $registry;

    protected $log;

    public function __construct(DataManagerRegistry $registry, Registry $doctrine, Logger $log)
    {
        $this->doctrine = $doctrine;
        $this->registry = $registry;
        $this->log = $log;
    }

    public function updateShortTimerCalls(Corporation $c, $force = false)
    {
        $calls = [
            AccountManager::getName() => 'updateAccounts',
            CorporationManager::getName() => ['getCorporationSheet', 'getMembers'],
            JournalTransactionManager::getName() => 'updateJournalTransactions',
            MarketTransactionManager::getName() => 'updateMarketTransactions',
            StarbaseManager::getName() => 'getStarbases',
        ];

        foreach ($calls as $manager => $call) {
            if (is_array($call)) {
                foreach ($call as $ic) {
                    if (!$this->checkShortTimer($c, $this->resolveCall($ic)) || $force === true) {
                        $this->doUpdate($manager, $ic, $c, ApiUpdate::CACHE_STYLE_SHORT);
                    }
                }
            } else {
                if (!$this->checkShortTimer($c, $this->resolveCall($call)) || $force === true) {
                    $this->doUpdate($manager, $call, $c, ApiUpdate::CACHE_STYLE_SHORT);
                }
            }
        }
    }

    public function updateLongTimerCalls(Corporation $c, $force = false)
    {
        $calls = [
            AssetManager::getName() => 'generateAssetList',
            MarketOrderManager::getName() => 'getMarketOrders',
            TitleManager::getName() => 'updateTitles',
        ];

        foreach ($calls as $manager => $call) {
            if (!$this->checkLongTimer($c, $this->resolveCall($call)) || $force === true) {
                $this->doUpdate($manager, $call, $c, ApiUpdate::CACHE_STYLE_LONG);
            }
        }
    }

    public function checkShortTimer(Corporation $c, $call)
    {
        return $this->doctrine->getRepository('AppBundle:ApiUpdate')
            ->getShortTimerExpired($c, $call);
    }

    public function checkLongTimer(Corporation $c, $call)
    {
        return $this->doctrine->getRepository('AppBundle:ApiUpdate')
            ->getLongTimerExpired($c, $call);
    }

    public function updateAssetCache(array $c, $force = false)
    {
        $this->registry->get(AssetManager::getName())
            ->updateAssetGroupCache($c, $force);
    }

    public function createApiUpdate($type, $call, $success, Corporation $corp = null)
    {
        $access = new ApiUpdate();

        $access->setType($type)
            ->setApiCall($call)
            ->setSucceeded($success);

        if ($corp) {
            $access->setCorporation($corp);
        }

        return $access;
    }

    protected function doUpdate($manager, $call, Corporation $c, $cache_style)
    {
        $this->log->info(sprintf('Executing %s', $call));
        $em = $this->doctrine->getManager();

        $start = microtime(true);

        $key = $this->registry->get($manager)->getApiKey($c);

        if ($key->getInvalid()) {
            $this->log->info(sprintf('Invalid Api key for %c', $c->getCorporationDetails()->getName()));

            return;
        }

        if ($key->getErrorCount() >= 5) {
            $key->setInvalid(true)
                ->setErrorCount(0);

            $em->persist($key);

            return;
        }

        $success = $this->tryCall($manager, $call, $c, $key);

        if (!$success) {
            $em->persist($key);
        }

        $update = $this->createApiUpdate(
            $cache_style,
            $this->resolveCall($call),
            $success,
            $c
        );

        $c->addApiUpdate($update);

        $em->persist($c);

        $end = microtime(true) - $start;
        $this->log->info(sprintf('Done Executing %s in %s seconds', $call, $end));
    }

    protected function tryCall($manager, $function, $arg, ApiCredentials $key)
    {
        try {
            $this->registry
                ->get($manager)
                ->$function($arg);

            return true;
        } catch (\Exception $e) {
            $this->log->error(sprintf('Error syncing data for %s  on call %s with: %s',
                $arg->getCorporationDetails()->getName(),
                $function,
                $e->getMessage()
            ));

            $count = $key->getErrorCount();
            $key->setErrorCount($count + 1);

            return false;
        }
    }

    protected function resolveCall($call)
    {
        switch ($call) {
            case 'updateAccounts':
                return ApiUpdate::CORP_ACC_BALANCES;
            case 'updateJournalTransactions':
                return ApiUpdate::CORP_WALLET_JOURNAL;
            case 'updateMarketTransactions':
                return ApiUpdate::CORP_WALLET_TRANSACTION;
            case 'generateAssetList':
                return ApiUpdate::CORP_ASSET_LIST;
            case 'getMarketOrders':
                return ApiUpdate::CORP_MARKET_ORDERS;
            case 'getStarbases':
                return ApiUpdate::CORP_STARBASE_LIST;
            case 'getMembers':
                return ApiUpdate::CORP_MEMBERS;
            case 'getCorporationSheet':
                return ApiUpdate::CORP_DETAILS;
            case 'updateRefTypes':
                return ApiUpdate::REF_TYPES;
            case 'updateTitles':
                return ApiUpdate::CORP_TITLES;
            case 'updateConquerableStations':
                return ApiUpdate::CONQUERABLE_STATIONS;
        }
    }
}
