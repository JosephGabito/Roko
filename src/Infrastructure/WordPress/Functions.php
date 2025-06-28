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

function roko_get_http_query( $query_string, $mode = INPUT_GET, $filter = FILTER_UNSAFE_RAW ) {
	$value = sanitize_text_field( wp_unslash( filter_input( $mode, $query_string, $filter ) ) );
	return roko_nullable( $value );
}

function roko_get_http_post( $post_string, $mode = INPUT_POST, $filter = FILTER_UNSAFE_RAW ) {
	$value = sanitize_text_field( wp_unslash( filter_input( $mode, $post_string, $filter ) ) );
	return roko_nullable( $value );
}

function roko_get_http_request_textarea( $request_string, $mode = INPUT_GET, $filter = FILTER_UNSAFE_RAW ) {
	$value = sanitize_textarea_field( wp_unslash( filter_input( $mode, $request_string, $filter ) ) );
	return roko_nullable( $value );
}

function roko_get_http_request_checkbox( $request_string, $mode = INPUT_POST, $filter = FILTER_UNSAFE_RAW ) {
	$value = sanitize_textarea_field( wp_unslash( filter_input( $mode, $request_string, $filter ) ) );
	return roko_nullable( $value );
}

function roko_nullable( $value ) {
	if ( empty( $value ) ) {
		return null;
	}

	return $value;
}
