<?php declare(strict_types=1);
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(
    name: 'DownloadDomains',
    description: 'Add a short description for your command',
)]
class DownloadDomainsCommand extends BaseCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        parent::execute($input, $output);

        $this->fxTitle("Retriving domains from GoDaddy...");
        $arrDomains = $this->GoDaddy->getDomains();

        $this->fxTitle("Building header...");
        $arrHeader  = [ array_keys($arrDomains[0]) ];
        $arrDomains = array_merge($arrHeader, $arrDomains);

        $this->writeCsvToVarArrayPath([static::CSV_OUT_FOLDER_NAME, 'domains.csv'], $arrDomains, false, static::CSV_DEFAULT_DELIMITER);

        return $this->endWithSuccess();
    }
}
