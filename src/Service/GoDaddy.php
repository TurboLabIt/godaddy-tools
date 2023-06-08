<?php declare(strict_types=1);
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;


class GoDaddy
{
    protected ResponseInterface $response;


    public function __construct(protected array $arrConfig, protected HttpClientInterface $httpClient)
    { }


    public function getDomains() : array
    {
        // 📚 https://developer.godaddy.com/doc/endpoint/domains#/v1/list
        $endpointAction = 'domains?statuses=ACTIVE,PENDING_DNS_ACTIVE&limit=750&includes=nameServers';
        return $this->makeRequest($endpointAction);
    }


    public function get3rdLevelDomains(string $domain) : array
    {
        // 📚 https://developer.godaddy.com/doc/endpoint/domains#/v1/recordGet
        $endpointAction = 'domains/' . $domain . "/records";
        return $this->makeRequest($endpointAction);
    }


    public function getResponse() : ResponseInterface
    {
        return $this->response;
    }


    protected function makeRequest(string $endpointAction) : array
    {
        $this->response =
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

            $content = $this->response->toArray();
            return $content;
    }
}
