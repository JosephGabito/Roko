<?php
namespace JosephG\Roko\Domain\Ingress;

class IngressConfig {

	public static function url(): string {
		if ( defined( 'ROKO_INGRESS_URL' ) && ROKO_INGRESS_URL ) {
			return ROKO_INGRESS_URL;
		}

		return 'http://localhost:8000';
	}
}
