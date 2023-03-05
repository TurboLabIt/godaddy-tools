<?php declare(strict_types=1);
namespace App\Command;

use TurboLabIt\PhpSymfonyBasecommand\Command\AbstractBaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\GoDaddy;
use League\Csv\Writer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

abstract class BaseCommand extends AbstractBaseCommand
{
    const CSV_OUT_FOLDER_NAME   = 'csv_output';
    const CSV_DEFAULT_DELIMITER = '|';


    public function __construct(
        protected array $arrConfig = [],
        protected GoDaddy $GoDaddy, protected ParameterBagInterface $parameterBag
    ) {
        parent::__construct($arrConfig);
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        parent::execute($input, $output);
        return AbstractBaseCommand::SUCCESS;
    }
}
