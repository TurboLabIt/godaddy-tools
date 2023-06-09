<?php declare(strict_types=1);
namespace App\Command;

use TurboLabIt\BaseCommand\Command\AbstractBaseCommand;
use App\Service\GoDaddy;
use TurboLabIt\BaseCommand\Service\ProjectDir;


abstract class BaseCommand extends AbstractBaseCommand
{
    const CSV_OUT_FOLDER_NAME   = 'domains';
    const CSV_DEFAULT_DELIMITER = '|';


    public function __construct(protected GoDaddy $GoDaddy, protected ?ProjectDir $projectDir)
    {
        parent::__construct([], null, null, $projectDir);
    }
}
