<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Client;

class Service
{

  /** @var int */
  private $id;

  /** @var int */
  private $userId;

  /** @var array */
  private $options;

  public function __construct(int $id, int $userId, array $options)
  {
    $this->id = $id;
    $this->userId = $userId;
    $this->options = $options;
  }

  public static function fromArray(array $data): self
  {
    return new self($data['id'] ?? 0, $data['userId'] ?? 0, (array)($data['options'] ?? []));
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getUserId(): int
  {
    return $this->userId;
  }

  public function isAtSiteLimit(int $number): bool
  {
    return ($this->options['sites'] ?? 0) >= $number;
  }

  public function toArray(): array
  {
    return ['id' => $this->id, 'userId' => $this->userId, 'options' => $this->options];
  }

}
