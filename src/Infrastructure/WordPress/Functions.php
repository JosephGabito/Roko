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
	// Check if the input exists first
	if ( ! filter_has_var( $mode, $query_string ) ) {
		return null;
	}

	$value = filter_input( $mode, $query_string, $filter );
	if ( null === $value || false === $value ) {
		return null;
	}

	return sanitize_text_field( wp_unslash( $value ) );
}

function roko_get_http_post( $post_string, $mode = INPUT_POST, $filter = FILTER_UNSAFE_RAW ) {
	// Check if the input exists first
	if ( ! filter_has_var( $mode, $post_string ) ) {
		return null;
	}

	$value = filter_input( $mode, $post_string, $filter );
	if ( null === $value || false === $value ) {
		return null;
	}

	return sanitize_text_field( wp_unslash( $value ) );
}

function roko_get_http_request_textarea( $request_string, $mode = INPUT_GET, $filter = FILTER_UNSAFE_RAW ) {
	if ( ! filter_has_var( $mode, $request_string ) ) {
		return null;
	}

	$value = filter_input( $mode, $request_string, $filter );
	if ( null === $value || false === $value ) {
		return null;
	}

	return sanitize_textarea_field( wp_unslash( $value ) );
}

function roko_get_http_request_checkbox( $request_string, $mode = INPUT_POST, $filter = FILTER_VALIDATE_BOOLEAN ) {
	if ( ! filter_has_var( $mode, $request_string ) ) {
		return false; // Checkboxes default to false when not present
	}

	$value = filter_input( $mode, $request_string, $filter, FILTER_NULL_ON_FAILURE );
	return null !== $value ? (bool) $value : false;
}
