<?php

namespace AppBundle\Service\DataManager;

use AppBundle\Entity\Character;
use Monolog\Logger;

class CharacterManager
{
    private $log;

    public function __construct(Logger $logger)
    {
        $this->log = $logger;
    }

    public function createCharacter(array $details)
    {
        $char = new Character();

        $char->setEveId($details['characterID'])
            ->setName($details['characterName'])
            ->setCorporationName($details['corporationName'])
            ->setEveCorporationId($details['corporationID']);

        return $char;
    }

    public function newCharacterWithName(array $details)
    {
        $char = new Character();

        $char->setEveId($details['id'])
            ->setName($details['name']);

        return $char;
    }
}
