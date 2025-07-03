<?php
declare(strict_types=1);

namespace JosephG\Roko\Domain\Security\Checks\ValueObject;

use JosephG\Roko\Domain\Security\Checks\ValueObject\Async;

/**
 * Value object representing a single security check.
 *
 * Maps to JSON schema check structure with all required fields.
 */
final class Check {

	public function __construct(
		private string $id,
		private string $label,
		private CheckStatus $status,
		private Severity $severity,
		private string $description,
		private ?array $evidence,
		private string $recommendation,
		private string $source,
		private Async $async,
		private ?int $scanTimeMs = null,
		private ?int $weight = null,
		private ?array $fix = null,
	) {}

	public function getId(): string {
		return $this->id;
	}

	public function getLabel(): string {
		return $this->label;
	}

	public function getStatus(): CheckStatus {
		// Mark the status as pending if its an async request.
		if ( $this->async->isAsync() ) {
			$this->status = CheckStatus::pending();
		}

		return $this->status;
	}

	public function getSeverity(): Severity {
		// Mark the status as pending if its an async request.
		if ( $this->async->isAsync() ) {
			$this->severity = Severity::pending();
		}
		return $this->severity;
	}

	public function getDescription(): string {
		return $this->description;
	}

	public function getEvidence(): ?array {
		return $this->evidence;
	}

	public function getRecommendation(): string {
		return $this->recommendation;
	}

	public function getSource(): string {
		return $this->source;
	}

	public function getScanTimeMs(): ?int {
		return $this->scanTimeMs;
	}

	public function getWeight(): ?int {
		return $this->weight;
	}

	public function getFix(): ?array {
		return $this->fix;
	}

	public function getAsync(): Async {
		return $this->async;
	}

	/**
	 * Convert to array format matching JSON schema.
	 */
	public function toArray(): array {
		$result = array(
			'id'             => $this->id,
			'label'          => $this->label,
			'status'         => $this->status->value(),
			'severity'       => $this->severity->value(),
			'description'    => $this->description,
			'evidence'       => $this->evidence,
			'recommendation' => $this->recommendation,
			'source'         => $this->source,
			'async'          => $this->async->toArray(),
		);

		if ( $this->scanTimeMs !== null ) {
			$result['scanTimeMs'] = $this->scanTimeMs;
		}

		if ( $this->weight !== null ) {
			$result['weight'] = $this->weight;
		}

		if ( $this->fix !== null ) {
			$result['fix'] = $this->fix;
		}

		return $result;
	}
}
