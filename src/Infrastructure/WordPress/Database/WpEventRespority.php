<?php
namespace JosephG\Roko\Infrastructure\WordPress\Database;

use DateTimeImmutable;
use JosephG\Roko\Domain\Event\Repository\EventRepositoryInterface;
use JosephG\Roko\Domain\Event\Entity\Event;
use JosephG\Roko\Domain\Event\ValueObject\EventType;
use JosephG\Roko\Domain\Event\ValueObject\Integration;
use JosephG\Roko\Domain\Event\ValueObject\OccuredAt;
use JosephG\Roko\Domain\Event\ValueObject\Payload;
use JosephG\Roko\Domain\Event\ValueObject\SiteId;

class WpEventRespority implements EventRepositoryInterface {

	private $table;
	private $wpdb;

	public function __construct() {
		global $wpdb;

		$this->table = $wpdb->prefix . 'roko_events';
		$this->wpdb  = $wpdb;
	}

	public function save( Event $event ): void {
		$data = array(
			'site_id'     => $event->siteId()->value(),
			'integration' => $event->integration()->value(),
			'event_type'  => $event->eventType()->value(),
			'payload'     => wp_json_encode( $event->payload()->value() ),
			'occurred_at' => $event->occurredAt()->value()->format( 'c' ),
			'sent_at'     => null,
		);

		$this->wpdb->insert(
			$this->table,
			$data
		);
	}

	public function findAll(): array {
		return $this->wpdb->get_results(
			"SELECT * FROM {$this->table}"
		);
	}

	public function findUnsent(): array {
		$events_raw = $this->wpdb->get_results(
			"SELECT * FROM {$this->table} WHERE sent_at IS NULL",
			ARRAY_A
		);

		$events = array();

		foreach ( $events_raw as $event_raw ) {
			$events[] = new Event(
				new SiteId( $event_raw['site_id'] ),
				new Integration( $event_raw['integration'] ),
				new EventType( $event_raw['event_type'] ),
				new Payload( $event_raw['payload'] ),
				new OccuredAt( new DateTimeImmutable( $event_raw['occurred_at'] ) ),
			);
		}

		return $events;
	}
}
