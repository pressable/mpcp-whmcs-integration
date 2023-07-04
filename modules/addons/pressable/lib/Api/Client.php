<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Api;

use GuzzleHttp\Client as Guzzle;
use Psr\Http\Message\ResponseInterface;

class Client
{

  private const _AUTH_URL = 'https://my.pressable.com/auth/token';
  private const _TIMEOUT = 30;
  private const _URL = 'https://my.pressable.com/v1/';

  /** @var string */
  private $id;

  /** @var string */
  private $secret;

  /** @var ?string */
  private $token;

  public function __construct(string $id, string $secret)
  {
    $this->id = $id;
    $this->secret = $secret;
  }

  public function addSiteTag(int $siteId, string $tag): ResponseInterface
  {
    return $this->apiPost("sites/{$siteId}/tags", ['tag_names' => [$tag]]);
  }

  public function createSite(array $data): ResponseInterface
  {
    $whitelist = ['name', 'php_version', 'staging', 'install', 'datacenter_code'];
    $data = array_intersect_key($data, array_flip($whitelist));

    return $this->apiPost('sites', $data);
  }

  public function datacenterList(): ResponseInterface
  {
    return $this->apiGet('sites/datacenters');
  }

  public function deleteSite(int $id): ResponseInterface
  {
    return $this->apiDelete("sites/{$id}");
  }

  public function disableSite(int $id): ResponseInterface
  {
    return $this->apiPut("sites/{$id}/disable");
  }

  public function enableSite(int $id): ResponseInterface
  {
    return $this->apiPut("sites/{$id}/enable");
  }

  public function siteInstallOptionList(): ResponseInterface
  {
    return $this->apiGet('sites/install-options');
  }

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

  public function phpVersionsList(): ResponseInterface
  {
    return $this->apiGet('sites/php-versions');
  }

  private function apiDelete(string $resource): ResponseInterface
  {
    return $this->getConnection()->delete($resource);
  }

  private function apiGet(string $resource, ?array $data = null): ResponseInterface
  {
    $options = [];
    if (! empty($data)) {
      $options['query'] = $data;
    }

    return $this->getConnection()->get($resource, $options);
  }

  private function apiPost(string $resource, ?array $data = null): ResponseInterface
  {
    $options = [];
    if (! empty($data)) {
      $options['json'] = $data;
    }

    return $this->getConnection()->post($resource, $options);
  }

  private function apiPut(string $resource, ?array $data = null): ResponseInterface
  {
    $options = [];
    if (! empty($data)) {
      $options['json'] = $data;
    }

    return $this->getConnection()->put($resource, $options);
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
