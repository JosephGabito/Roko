<?php
namespace JosephG\Roko\Domain\Event\Repository;

use JosephG\Roko\Domain\Event\Entity\Event;

interface EventRepositoryInterface {
	public function save( Event $event ): void;
	public function findAll(): array;
	public function findUnsent(): array;
}
