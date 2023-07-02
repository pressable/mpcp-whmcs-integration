<?php

declare(strict_types = 1);

namespace Pressable\Whmcs\Api;

use GuzzleHttp\Client as Guzzle;
use Psr\Http\Message\ResponseInterface;

class Client
{

  private const _AUTH_URL = 'https://my.pressable.com/auth/token';
  private const _TIMEOUT = 30;
  private const _URL = 'https://my.pressable.com/v1/';

  private string $token;

  public function __construct(private string $id, private string $secret)
  {}

  public function siteList(array $query): ResponseInterface
  {
    $data = ['paginate' => true];

    if (isset($query['page'])) {
      $data['page'] = $query['page'];
    }
    if (isset($query['per_page'])) {
      $data['per_page'] = $query['per_page'];
    }

    return $this->apiGet('sites', $data);
  }

  private function apiGet(string $resource, ?array $data = null): ResponseInterface
  {
    $options = [];
    if (! empty($data)) {
      $options['query'] = $data;
    }

    return $this->getConnection()->get($resource, $options);
  }

  private function getAuthToken(): string
  {
    if (! isset($this->token)) {
      $response = $this->getConnection(false)->post(
        self::_AUTH_URL,
        [
          'form_params' => [
            'client_id' => $this->id,
            'client_secret' => $this->secret,
            'grant_type' => 'client_credentials',
          ],
        ]
      );

      $body = json_decode($response->getBody()->getContents(), true);

      if (isset($body['access_token'])) {
        $this->token = $body['access_token'];
      }
    }

    return $this->token;
  }

  private function getRequestHeaders(): array
  {
    return [
      'Accept' => 'application/json',
      'Authorization' => "Bearer {$this->getAuthToken()}",
    ];
  }

  private function getConnection(bool $withHeaders = true): Guzzle
  {
    $options = [
      'base_uri' => self::_URL,
      'connect_timeout' => self::_TIMEOUT,
      'timeout' => self::_TIMEOUT,
    ];
    if ($withHeaders) {
      $options['headers'] = $this->getRequestHeaders();
    }

    return new Guzzle($options);
  }

}
