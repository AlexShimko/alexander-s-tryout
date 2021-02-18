<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Transaction;
use App\Enum\ClientTypes;
use App\Enum\CurrencyEnum;
use App\Enum\OperationTypes;
use App\Model\Money;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // TODO from file to DB builder
        $client = new Client();
        $client->setClientType(ClientTypes::PRIVATE);

        $manager->persist($client);

        $transaction = new Transaction();
        $transaction->setClient($client);
        $transaction->setOperationAmount(new Money(100000, CurrencyEnum::EUR));
        $transaction->setZeroFeeAmount();
        $transaction->setOperationType(OperationTypes::WITHDRAW);
        $transaction->setDate(\DateTime::createFromFormat('Y-m-d', '2016-01-05'));

        $manager->persist($transaction);

        $manager->flush();
    }
}
