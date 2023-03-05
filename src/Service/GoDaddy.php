<?php declare(strict_types=1);
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;


class GoDaddy
{
    public function __construct(protected array $arrConfig = [], protected HttpClientInterface $httpClient)
    { }


    public function getDomains() : array
    {
        // 📚 https://developer.godaddy.com/doc/endpoint/domains
        $endpointAction = 'domains?statuses=ACTIVE&limit=750';
        return $this->makeRequest($endpointAction);
    }


    protected function makeRequest(string $endpointAction) : array
    {
        $response =
            $this->httpClient->request(
                'GET',
                $this->arrConfig["GoDaddy"]["endpoint"] . $endpointAction,
                [
                    'headers' => [
                        'Authorization: sso-key ' 
                            . $this->arrConfig["GoDaddy"]["key"] . ":" . $this->arrConfig["GoDaddy"]["secret"]
                    ]
                ]
            );

            $content = $response->toArray();
            return $content;
    }
}
