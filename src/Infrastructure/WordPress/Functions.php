<?php

use JosephG\Roko\Domain\Event\Entity\Event;
use JosephG\Roko\Domain\Event\ValueObject\EventType;
use JosephG\Roko\Domain\Event\ValueObject\Integration;
use JosephG\Roko\Domain\Event\ValueObject\SiteId;
use JosephG\Roko\Domain\Event\ValueObject\Payload;
use JosephG\Roko\Domain\Event\ValueObject\OccuredAt;
use JosephG\Roko\Infrastructure\WordPress\Database\WpEventRespority;

function roko_send_event( array $data ) {

	$event = new Event(
		new SiteId( $data['site_id'] ),
		new Integration( $data['integration'] ),
		new EventType( $data['event_type'] ),
		new Payload( $data['payload'] ),
		new OccuredAt( new \DateTimeImmutable( $data['occurred_at'] ) )
	);

	$hookArgs = array(
		'event' => $event,
		'data'  => $data,
	);

	do_action( 'roko_event_before_send', $hookArgs );

	$event_repository = new WpEventRespority();
	$event_repository->save( $event );

	do_action( 'roko_event_after_send', $hookArgs );
}
