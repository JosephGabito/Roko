<?php
namespace JosephG\Roko\Domain\Event\Entity;

use JosephG\Roko\Domain\Event\ValueObject\EventType;
use JosephG\Roko\Domain\Event\ValueObject\Integration;
use JosephG\Roko\Domain\Event\ValueObject\OccuredAt;
use JosephG\Roko\Domain\Event\ValueObject\Payload;
use JosephG\Roko\Domain\Event\ValueObject\SiteId;

class Event {

	private EventType $type;
	private Integration $integration;
	private OccuredAt $occurredAt;
	private Payload $payload;
	private SiteId $siteId;

	/**
	 * @param SiteId      $siteId
	 * @param Integration $integration
	 * @param EventType   $type
	 * @param Payload     $payload
	 * @param OccuredAt   $occurredAt
	 */
	public function __construct(
		SiteId $siteId,
		Integration $integration,
		EventType $type,
		Payload $payload,
		OccuredAt $occurredAt,
	) {
		$this->type        = $type;
		$this->integration = $integration;
		$this->occurredAt  = $occurredAt;
		$this->payload     = $payload;
		$this->siteId      = $siteId;
	}

	/**
	 * @return EventType
	 */
	public function eventType(): EventType {
		return $this->type;
	}

	/**
	 * @return Integration
	 */
	public function integration(): Integration {
		return $this->integration;
	}

	/**
	 * @return OccuredAt
	 */
	public function occurredAt(): OccuredAt {
		return $this->occurredAt;
	}

	/**
	 * @return Payload
	 */
	public function payload(): Payload {
		return $this->payload;
	}

	/**
	 * @return SiteId
	 */
	public function siteId(): SiteId {
		return $this->siteId;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return json_encode(
			array(
				'type'        => $this->type,
				'integration' => $this->integration,
				'occurredAt'  => $this->occurredAt,
				'payload'     => $this->payload,
				'siteId'      => $this->siteId,
			)
		);
	}
}
