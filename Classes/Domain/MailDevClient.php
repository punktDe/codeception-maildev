<?php
namespace PunktDe\Codeception\MailDev\Domain;

/*
 * This file is part of the PunktDe\Codeception-MailDev package.
 *
 * This package is open source software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use GuzzleHttp\Client;
use PunktDe\Codeception\MailDev\Domain\Model\Mail;

class MailDevClient
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * MailDevClient constructor.
     * @param string $baseUri
     */
    public function __construct(string $baseUri = 'http://127.0.0.1:8025')
    {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'cookies' => true,
            'headers' => [
                'User-Agent' => 'FancyPunktDeGuzzleTestingAgent'
            ],
        ]);
    }

    public function deleteAllMessages(): void
    {
        $this->client->delete('/api/v1/messages');
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function countAll(): int
    {
        $data = $this->getDataFromMailDev('api/v2/messages?start=0&limit=1');
        return (int) $data['total'];
    }

    /**
     * @param $index
     * @return Mail
     */
    public function findOneByIndex(int $index): Mail
    {
        $apiCall = sprintf('api/v2/messages', $index);
        $result = $this->client->get($apiCall)->getBody();

        if (($data = json_decode($result, true)) !== false) {
            $currentMailData = $data['items'][$index];
            return $this->buildMailObjectFromJson($currentMailData);
        }
    }

    /**
     * @param $apiCall
     * @return array
     * @throws \Exception
     */
    protected function getDataFromMailDev($apiCall): array
    {
        $result = $this->client->get($apiCall)->getBody();

        $data = json_decode($result, true);

        if ($data === false) {
            throw new \Exception('The maildev result could not be parsed to json', 1467038556);
        }

        return $data;
    }

    /**
     * @param array $data
     * @return Mail
     */
    protected function buildMailObjectFromJson(array $data): Mail
    {
        return new Mail($data);
    }

}