<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Api;

use Exception;
use GuzzleHttp\Client as Guzzle;
use Psr\Http\Message\ResponseInterface;

class Pressable
{

  public const SITE_TAG_CLIENT_PREFIX = 'whmcs.client.';
  public const SITE_TAG_SERVICE_PREFIX = 'whmcs.service.';

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

  public function addSiteDomain(int $siteId, string $domain): ResponseInterface
  {
    return $this->apiPost("sites/{$siteId}/domains", ['name' => $domain]);
  }

  public function addSiteTag(int $siteId, string $tag): ResponseInterface
  {
    return $this->apiPost("sites/{$siteId}/tags", ['tag_names' => [$tag]]);
  }

  public function createSite(array $data, int $clientId, int $serviceId): ResponseInterface
  {
    $whitelist = ['name', 'php_version', 'staging', 'install', 'datacenter_code'];
    $data = $this->whitelist($data, $whitelist);

    $response = $this->apiPost('sites', $data);
    $body = json_decode($response->getBody()->getContents(), true) ?? [];

    $siteId = (int)($body['data']['id'] ?? 0);
    if ($siteId > 0) {
      $prefix = self::SITE_TAG_CLIENT_PREFIX;
      $this->addSiteTag($siteId, "{$prefix}{$clientId}");

      $prefix = self::SITE_TAG_SERVICE_PREFIX;
      $this->addSiteTag($siteId, "{$prefix}{$serviceId}");
    }

    return $response;
  }

  public function datacenterList(): ResponseInterface
  {
    return $this->apiGet('sites/datacenters');
  }

  public function deleteSite(int $id): ResponseInterface
  {
    return $this->apiDelete("sites/{$id}");
  }

  public function deleteSiteDomain(int $siteId, int $domainId): ResponseInterface
  {
    return $this->apiDelete("sites/{$siteId}/domains/{$domainId}");
  }

  public function disableSite(int $id): ResponseInterface
  {
    return $this->apiPut("sites/{$id}/disable");
  }

  public function enableSite(int $id): ResponseInterface
  {
    return $this->apiPut("sites/{$id}/enable");
  }

  public function getSite(int $id): ResponseInterface
  {
    return $this->apiGet("sites/{$id}");
  }

  public function getSiteBackups(int $id): ResponseInterface
  {
    return $this->apiGet("sites/{$id}/backups");
  }

  public function getSiteDomains(int $id): ResponseInterface
  {
    return $this->apiGet("sites/{$id}/domains");
  }

  public function siteInstallOptionList(): ResponseInterface
  {
    return $this->apiGet('sites/install-options');
  }

  public function siteList(array $query): ResponseInterface
  {
    $whitelist = ['page', 'per_page', 'tag_name'];

    $data = array_merge(
      ['paginate' => true],
      $this->whitelist($query, $whitelist)
    );

    return $this->apiGet('sites', $data);
  }

  public function phpVersionsList(): ResponseInterface
  {
    return $this->apiGet('sites/php-versions');
  }

  public function resetWpPassword(int $siteId): ResponseInterface
  {
    return $this->apiPut("sites/{$siteId}/wordpress/password-reset");
  }

  public function restoreBackups(int $siteId, array $backups): ResponseInterface
  {
    $whitelist = ['filesystem_id', 'database_id'];
    $backups = $this->whitelist($backups, $whitelist);
    if (empty($backups)) {
      throw new Exception('Specify Backups to Restore');
    }

    return $this->apiPost("sites/{$siteId}/restores", $backups);
  }

  public function updateSite(int $siteId, array $data): ResponseInterface
  {
    return $this->apiPut("sites/{$siteId}", $data);
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
      'http_errors' => false,
    ];
    if ($withHeaders) {
      $options['headers'] = $this->getRequestHeaders();
    }

    return new Guzzle($options);
  }

  private function whitelist(array $data, array $whitelist): array
  {
    return array_intersect_key($data, array_flip($whitelist));
  }

}
