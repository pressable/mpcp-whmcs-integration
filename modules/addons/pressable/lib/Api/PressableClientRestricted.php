<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Api;

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

  public function __construct(string $id, string $secret)
  {
    $this->api = new Pressable($id, $secret);
    $this->client = (new CurrentUser())->client();
  }

  public function siteList(array $query): ResponseInterface
  {
    $query['tag_name'] = Pressable::SITE_TAG_CLIENT_PREFIX . $this->client->id;

    return $this->api->siteList($query);
  }

}
