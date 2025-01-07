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
     * @param string $username
     * @param string $password
     * @param string $authenticationType
     */
    public function __construct(string $baseUri, string $username = '', string $password = '', string $authenticationType = 'basic')
    {
        $configuration = [
                'base_uri' => $baseUri,
                'cookies' => true,
                'headers' => [
                    'User-Agent' => 'FancyPunktDeGuzzleTestingAgent'
                ],
            ];

        if($username !== '' && $password !== '') {
            $configuration = array_merge($configuration, ['auth' => [$username, $password, $authenticationType]]);
        }

        $this->client = new Client($configuration);
    }

    public function deleteAllMails(): void
    {
        $this->client->delete('/email/all');
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function countAll(): int
    {
        $data = $this->getDataFromMailDev('/email');

        return count($data);
    }

    /**
     * @param $index
     * @return Mail
     */
    public function findOneByIndex(int $index): Mail
    {
        $data = $this->getDataFromMailDev('/email');

        return new Mail($data[$index]);
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
