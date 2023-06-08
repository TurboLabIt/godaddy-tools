<?php declare(strict_types=1);
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;


#[AsCommand(
    name: 'DownloadDomains',
    description: 'Add a short description for your command',
)]
class DownloadDomainsCommand extends BaseCommand
{
    const CSV_COLUMNS_2ND_LEVEL_DOMAINS = [
        'createdAt' => null, 'domain' => null, 'status' => null, 'expires' => null,
        'nameServers' => null, 'renewAuto' => null
    ];

    const CSV_COLUMNS_3RD_LEVEL_DOMAINS = [
        'name' => null, 'ttl' => null, 'type' => null, 'data' => null,
    ];


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        parent::execute($input, $output);

        $this->fxTitle("Retriving domains from GoDaddy...");
        $arrDomains = $this->goDaddyApiCall(function($me) {
            return $me->GoDaddy->getDomains();
        });

        $this
            ->fxTitle("Filtering data...")
            ->filter2ndLevelDomainColumns($arrDomains);

        $this->add3rdLevelDomains($arrDomains);

        $this
            ->fxTitle("Converting arrays to strings...")
            ->arrayToString($arrDomains);

        $this->fxTitle("Building header...");
        $arrHeader  = [ array_keys($arrDomains[0]) ];
        $arrDomains = array_merge($arrHeader, $arrDomains);

        $this->fxTitle("Output...");
        $arrCsvPath = [
            static::CSV_OUT_FOLDER_NAME, date("Y-m-d") . "-domains.csv"
        ];

        $this->writeCsvToVarArrayPath($arrCsvPath, $arrDomains, false, static::CSV_DEFAULT_DELIMITER);

        return $this->endWithSuccess();
    }


    protected function goDaddyApiCall(callable $fx, ...$fxArguments) : array
    {
        try {

            return $fx($this, ...$fxArguments);

        } catch(ClientException $ex) {

            $response   = $this->GoDaddy->getResponse();
            $httpStatus = $response->getStatusCode();

            switch($httpStatus) {

                // HTTP/1.1 429 Too Many Requests returned
                 case Response::HTTP_TOO_MANY_REQUESTS: {

                    $this->fxWarning( $ex->getMessage() );
                    $this->fxInfo("Waiting and retrying...");

                    $content = $response->toArray(false);
                    $sleepForSec = $content["retryAfterSec"] ?? 45;

                    // add some more dalay, just in case
                    $sleepForSec += 3;

                    $countdownSection = $this->output->section();

                    for(; $sleepForSec > 0; $sleepForSec--) {
                        $countdownSection->overwrite("⏳ $sleepForSec");
                        sleep(1);
                    }

                    return $this->goDaddyApiCall($fx, ...$fxArguments);
                 }
            }

            throw $ex;
        }
    }


    protected function filter2ndLevelDomainColumns(array &$arrDomains) : self
    {
        $arrCleanDomains = [];
        foreach($arrDomains as $arrRow) {

            $arrDomain =
                array_intersect_key(
                    // https://www.php.net/manual/en/function.array-intersect-key.php#126352
                    array_replace(self::CSV_COLUMNS_2ND_LEVEL_DOMAINS, $arrRow),
                    self::CSV_COLUMNS_2ND_LEVEL_DOMAINS
                );

            $arrDomain          = array_merge($arrDomain, static::CSV_COLUMNS_3RD_LEVEL_DOMAINS);
            $arrCleanDomains[]  = $arrDomain;
        }

        $arrDomains = $arrCleanDomains;
        return $this;
    }


    protected function add3rdLevelDomains(array &$arrDomains) : self
    {
        $arrDomainsWith3rdLevels = [];
        foreach($arrDomains as $arrRow) {

            $arrDomainsWith3rdLevels[] = $arrRow;
            $domain = $arrRow["domain"];

            $this->fxTitle("Retriving 3rd level domains from GoDaddy for ##" . $domain . "##...");

            try {

                $arrDataFromGoDaddy = $this->goDaddyApiCall(function($me, $domain) {
                    return $me->GoDaddy->get3rdLevelDomains($domain);
                }, $domain);

            } catch(ClientException) {

                $response   = $this->GoDaddy->getResponse();
                $httpStatus = $response->getStatusCode();

                switch($httpStatus) {

                    // this domain has no records (it's probably used just for 3rd-party NS management)
                    case Response::HTTP_NOT_FOUND: {

                        $this->fxWarning("This domain has no 3rd levels!");
                        continue 2;
                    }
                }
            }


            $this->fxTitle("Adding 3rd level domains for ##" . $domain . "##...");
            foreach($arrDataFromGoDaddy as $arrOne3rdLevelData) {
                $this->add3rdLevelDomain($domain, $arrOne3rdLevelData, $arrDomainsWith3rdLevels);
            }
        }

        $arrDomains = $arrDomainsWith3rdLevels;
        return $this;
    }


    protected function add3rdLevelDomain(string $domain, array $arrOne3rdLevelData, array &$arrDomains) : self
    {
        $arrRow = self::CSV_COLUMNS_2ND_LEVEL_DOMAINS;
        $arrRow["domain"] = $domain;

        $arrCleanDomain =
            array_intersect_key(
                // https://www.php.net/manual/en/function.array-intersect-key.php#126352
                array_replace(self::CSV_COLUMNS_3RD_LEVEL_DOMAINS, $arrOne3rdLevelData),
                self::CSV_COLUMNS_3RD_LEVEL_DOMAINS
            );

        $arrCleanDomain = array_merge($arrRow, $arrCleanDomain);

        $arrDomains[] = $arrCleanDomain;
        return $this;
    }


    protected function arrayToString(array &$domains) : static
    {
        foreach($domains as &$domain) {
            foreach($domain as &$oneRow) {
                if( is_array($oneRow) ) {
                    $oneRow = implode(PHP_EOL, $oneRow);
                }
            }
        }

        return $this;
    }
}
