<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Api;

use Exception;
use Psr\Http\Message\ResponseInterface;
use WHMCS\Authentication\CurrentUser;

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

  public function __construct(string $id, string $secret)
  {
    $this->api = new Pressable($id, $secret);
    $this->client = (new CurrentUser())->client();
  }

  public function getSite(int $id): ResponseInterface
  {
    $this->assertSiteRestriction($id);

    return $this->api->getSite($id);
  }

  public function siteList(array $query): ResponseInterface
  {
    $query['tag_name'] = Pressable::SITE_TAG_CLIENT_PREFIX . $this->client->id;

    return $this->api->siteList($query);
  }

  private function assertSiteRestriction(int $id): void
  {
    if (! in_array($id, $this->getClientSiteIds())) {
      throw new Exception('Denied');
    }
  }

  private function getClientSiteIds(): array
  {
    if (! isset($this->client_site_ids)) {
      $response = $this->siteList(['paginate' => false]);
      $body = json_decode($response->getBody()->getContents(), true);
      $list = $body['data'] ?? [];

      $this->client_site_ids = array_map(static function ($site) {
        return $site['id'];
      }, $list);
    }

    return $this->client_site_ids;
  }

}
