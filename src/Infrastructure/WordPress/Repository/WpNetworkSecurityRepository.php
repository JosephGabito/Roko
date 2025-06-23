<?php
namespace JosephG\Roko\Infrastructure\WordPress\Repository;

use JosephG\Roko\Domain\Security\NetworkSecurity\Repository\NetworkSecurityRepositoryInterface;
use JosephG\Roko\Domain\Security\NetworkSecurity\Entity\SslCertificate;
use JosephG\Roko\Domain\Security\NetworkSecurity\ValueObject\DomainName;

final class WpNetworkSecurityRepository implements NetworkSecurityRepositoryInterface {

	public function currentState(): object {
		$domain = new DomainName( parse_url( home_url(), PHP_URL_HOST ) );
		$cert   = $this->probeSslCert( $domain->value );

		return (object) array(
			'httpsEnforced'        => is_ssl(),
			'sslValid'             => $cert->valid,
			'hsts'                 => $this->hasHstsHeader(),
			'securityHeadersCount' => $this->header_score(),
			'certificate'          => $cert,
		);
	}

	private function probeSslCert( string $domain ): SslCertificate {
		// minimal check using stream context
		$context = stream_context_create(
			array(
				'ssl' => array(
					'capture_peer_cert' => true,
					'verify_peer'       => false,
					'verify_peer_name'  => false,
				),
			)
		);
		$client  = @stream_socket_client( "ssl://{$domain}:443", $errno, $errstr, 2, STREAM_CLIENT_CONNECT, $context );
		if ( ! $client ) {
			return new SslCertificate( new DomainName( $domain ), false, new \DateTimeImmutable( '@0' ) );
		}
		$params = stream_context_get_params( $client );
		$cert   = openssl_x509_parse( $params['options']['ssl']['peer_certificate'] );
		return new SslCertificate(
			new DomainName( $domain ),
			isset( $cert['validTo_time_t'] ) && $cert['validTo_time_t'] > time(),
			new \DateTimeImmutable( '@' . ( $cert['validTo_time_t'] ?? 0 ) )
		);
	}

	private function hasHstsHeader(): bool {
		$headers = get_headers( home_url(), true );
		return isset( $headers['Strict-Transport-Security'] );
	}

	private function header_score(): int {
		$wanted_headers = array(
			'X-Frame-Options',
			'X-Content-Type-Options',
			'Referrer-Policy',
			'Permissions-Policy',
			'Content-Security-Policy',
			'Strict-Transport-Security',
		);

		$site_headers = get_headers( home_url(), true );

		if ( ! $site_headers ) {
			return 0;
		}

		// Convert headers to lowercase for comparison.
		$site_headers_lower = array_change_key_case( $site_headers, CASE_LOWER );

		$found_headers = 0;
		foreach ( $wanted_headers as $header ) {
			if ( isset( $site_headers_lower[ strtolower( $header ) ] ) ) {
				++$found_headers;
			}
		}

		return $found_headers;
	}
}
