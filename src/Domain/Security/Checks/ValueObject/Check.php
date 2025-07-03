<?php

namespace JosephG\Roko\Domain\Security\Checks\ValueObject;

use JosephG\Roko\Domain\Security\Checks\ValueObject\Async;

/**
 * Value object representing a single security check.
 *
 * Maps to JSON schema check structure with all required fields.
 */
final class Check {

	private $id;
	private $label;
	private $status;
	private $severity;
	private $description;
	private $evidence;
	private $recommendation;
	private $source;
	private $async;
	private $scanTimeMs;
	private $weight;
	private $fix;

	public function __construct(
		$id,
		$label,
		CheckStatus $status,
		Severity $severity,
		$description,
		$evidence,
		$recommendation,
		$source,
		Async $async,
		$scanTimeMs = null,
		$weight = null,
		$fix = null
	) {
		$this->id             = $id;
		$this->label          = $label;
		$this->status         = $status;
		$this->severity       = $severity;
		$this->description    = $description;
		$this->evidence       = $evidence;
		$this->recommendation = $recommendation;
		$this->source         = $source;
		$this->async          = $async;
		$this->scanTimeMs     = $scanTimeMs;
		$this->weight         = $weight;
		$this->fix            = $fix;
	}

	public function getId() {
		return $this->id;
	}

	public function getLabel() {
		return $this->label;
	}

	public function getStatus() {
		// Return computed status based on async state - don't mutate!
		if ( $this->async->isAsync() ) {
			return CheckStatus::pending();
		}
		return $this->status;
	}

	public function getSeverity() {
		// Return computed severity based on async state - don't mutate!
		if ( $this->async->isAsync() ) {
			return Severity::pending();
		}
		return $this->severity;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getEvidence() {
		return $this->evidence;
	}

	public function getRecommendation() {
		return $this->recommendation;
	}

	public function getSource() {
		return $this->source;
	}

	public function getScanTimeMs() {
		return $this->scanTimeMs;
	}

	public function getWeight() {
		return $this->weight;
	}

	public function getFix() {
		return $this->fix;
	}

	public function getAsync() {
		return $this->async;
	}

	/**
	 * Convert to array format matching JSON schema.
	 */
	public function toArray() {
		$result = array(
			'id'             => $this->id,
			'label'          => $this->label,
			'status'         => $this->getStatus()->value(),  // Use computed getter
			'severity'       => $this->getSeverity()->value(), // Use computed getter
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
