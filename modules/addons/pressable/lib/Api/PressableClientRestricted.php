<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Api;

use Exception;
use Psr\Http\Message\ResponseInterface;
use WHMCS\Authentication\CurrentUser;
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

  /** @var ?Service */
  private $service;

  public function __construct(string $id, string $secret, ?Service $service = null)
  {
    $this->api = new Pressable($id, $secret);
    $this->client = (new CurrentUser())->client();
    $this->service = $service;
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

  public function resetWpPassword(int $siteId): ResponseInterface
  {
    $this->assertSiteRestriction($siteId);

    return $this->api->resetWpPassword($siteId);
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
      $body = json_decode($response->getBody()->getContents(), true);
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
