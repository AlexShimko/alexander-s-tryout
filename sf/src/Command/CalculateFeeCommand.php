<?php

declare(strict_types=1);

namespace App\Command;

use App\Facade\FeeCalculationFacade;
use App\Repository\TransactionRepository;
use http\Exception\InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CalculateFeeCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'fee:calculate';

    /**
     * @var FeeCalculationFacade
     */
    private FeeCalculationFacade $feeCalculationFacade;

    private $repo;

    /**
     * CalculateFeeCommand constructor.
     * @param FeeCalculationFacade $feeCalculationFacade
     */
    public function __construct(FeeCalculationFacade $feeCalculationFacade, TransactionRepository $repo)
    {
        $this->feeCalculationFacade = $feeCalculationFacade;
        $this->repo = $repo;
        parent::__construct(self::$defaultName);
    }

    /**
     * Command configuration
     */
    protected function configure()
    {
        $this
            ->setDescription('Calculating fees for transaction provided in input file.')
            ->addOption('file', 'f', InputArgument::OPTIONAL, 'CSV filename with transactions.', 'storage/input.csv')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        var_dump($this->repo->findAll());

        return 1;

        $uploadedFile = new UploadedFile($input->getOption('file'), $input->getOption('file'));
        if (!$uploadedFile) {
            throw new InvalidArgumentException('"file" is required');
        }

        $result = $this->feeCalculationFacade->calculateFeesFromFile($uploadedFile);

        foreach ($result as $fee) {
            $output->writeln($fee);
        }

        return 0;
    }
}
