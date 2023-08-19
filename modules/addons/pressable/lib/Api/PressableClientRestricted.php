<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Api;

use Exception;
use Psr\Http\Message\ResponseInterface;
use WHMCS\Authentication\CurrentUser;
use WHMCS\Module\Addon\Pressable\Api\Error\Pressable as PressableError;
use WHMCS\Module\Addon\Pressable\Client\Service;

/**
 * A client restricted interface to the Pressable API
 *
 * Yes, an interface to the interface. Ensures we're not acting on things we shouldn't.
 */
class PressableClientRestricted
{

  /** @var Pressable */
  private $api;

  /** @var object */
  private $client;

  /** @var ?array */
  private $client_site_ids;

  /** @var bool */
  private $isAdmin = false;

  /** @var ?Service */
  private $service;

  public function __construct(string $id, string $secret, ?Service $service = null)
  {
    $this->api = new Pressable($id, $secret);
    $this->service = $service;

    $user = new CurrentUser();
    $this->client = $user->client();
    $this->isAdmin = $user->isMasqueradingAdmin();
  }

  public function addSiteDomain(int $siteId, string $domain): ResponseInterface
  {
    $this->assertSiteRestriction($siteId);

    return $this->api->addSiteDomain($siteId, $domain);
  }

  public function createSite(array $data): ResponseInterface
  {
    return $this->api->createSite($data, $this->client->id, $this->getServiceId());
  }

  public function datacenterList(): ResponseInterface
  {
    return $this->api->datacenterList();
  }

  public function deleteSite(int $id): ResponseInterface
  {
    $this->assertSiteRestriction($id);

    return $this->api->deleteSite($id);
  }

  public function deleteSiteDomain(int $siteId, int $domainId): ResponseInterface
  {
    $this->assertSiteRestriction($siteId);

    return $this->api->deleteSiteDomain($siteId, $domainId);
  }

  public function disableSite(int $id): ResponseInterface
  {
    $this->assertAdmin();
    $this->assertSiteRestriction($id);

    return $this->api->disableSite($id);
  }

  public function enableSite(int $id): ResponseInterface
  {
    $this->assertAdmin();
    $this->assertSiteRestriction($id);

    return $this->api->enableSite($id);
  }

  public function getFtpUsers(int $id): ResponseInterface
  {
    $this->assertSiteRestriction($id);

    return $this->api->getFtpUsers($id);
  }

  public function getPhpMyAdminUrl(int $id): ResponseInterface
  {
    $this->assertSiteRestriction($id);

    return $this->api->getPhpMyAdminUrl($id);
  }

  public function getSite(int $id): ResponseInterface
  {
    $this->assertSiteRestriction($id);

    return $this->api->getSite($id);
  }

  public function getSiteBackups(int $id): ResponseInterface
  {
    $this->assertSiteRestriction($id);

    return $this->api->getSiteBackups($id);
  }

  public function getSiteDomains(int $id): ResponseInterface
  {
    $this->assertSiteRestriction($id);

    return $this->api->getSiteDomains($id);
  }

  public function phpVersionsList(): ResponseInterface
  {
    return $this->api->phpVersionsList();
  }

  public function resetFtpPassword(int $siteId, string $username): ResponseInterface
  {
    $this->assertSiteRestriction($siteId);

    return $this->api->resetFtpPassword($siteId, $username);
  }

  public function resetWpPassword(int $siteId): ResponseInterface
  {
    $this->assertSiteRestriction($siteId);

    return $this->api->resetWpPassword($siteId);
  }

  public function restoreBackups(int $siteId, array $backups): ResponseInterface
  {
    $this->assertSiteRestriction($siteId);

    return $this->api->restoreBackups($siteId, $backups);
  }

  public function siteCount(): int
  {
    $query = ['paginate' => true, 'per_page' => 0];
    $response = $this->siteList($query);
    if ($response->getStatusCode() >= 400) {
      throw PressableError::fromResponse($response);
    }

    $body = json_decode((string)$response->getBody(), true);

    return (int)($body['page']['totalItems']);
  }

  public function siteList(array $query): ResponseInterface
  {
    $query['tag_name'] = Pressable::SITE_TAG_SERVICE_PREFIX . $this->getServiceId();

    return $this->api->siteList($query);
  }

  public function siteInstallOptionList(): ResponseInterface
  {
    return $this->api->siteInstallOptionList();
  }

  public function updateSite(int $siteId, array $data): ResponseInterface
  {
    $this->assertSiteRestriction($siteId);

    return $this->api->updateSite($siteId, $data);
  }

  public function validateSiteName(string $name): ResponseInterface
  {
    return $this->api->validateSiteName($name);
  }

  private function assertAdmin(): void
  {
    if (! $this->isAdmin) {
      throw new Exception('Denied');
    }
  }

  private function assertSiteRestriction(int $id): void
  {
    if (! in_array($id, $this->getClientSiteIds())) {
      throw new Exception('Denied');
    }
  }

  private function clientSiteList(array $query): ResponseInterface
  {
    $query['tag_name'] = Pressable::SITE_TAG_CLIENT_PREFIX . $this->client->id;

    return $this->api->siteList($query);
  }

  private function getClientSiteIds(): array
  {
    if (! isset($this->client_site_ids)) {
      $response = $this->clientSiteList(['paginate' => false]);
      $body = json_decode((string)$response->getBody(), true);
      $list = $body['data'] ?? [];

      $this->client_site_ids = array_map(static function ($site) {
        return $site['id'];
      }, $list);
    }

    return $this->client_site_ids;
  }

  private function getServiceId(): int
  {
    $id = isset($this->service)
        ? $this->service->getId()
        : 0;

    if ($id <= 0) {
      throw new Exception('Service Required');
    }

    return $id;
  }

}
