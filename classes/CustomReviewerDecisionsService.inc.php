<?php

/**
 * @file plugins/generic/customReviewerDecisionsPlugin/classes/CustomReviewerDecisionsService.inc.php
 *
 * Copyright (c) 2026
 * Distributed under the GNU GPL v3.
 *
 * @class CustomReviewerDecisionsService
 * @ingroup plugins_generic_customReviewerDecisionsPlugin
 *
 * @brief Service class for managing custom reviewer decisions, localization, and defaults.
 */

class CustomReviewerDecisionsService {
	/** @var CustomReviewerDecisionsPlugin */
	protected $_plugin;

	/**
	 * Constructor
	 * @param CustomReviewerDecisionsPlugin $plugin
	 */
	public function __construct($plugin) {
		$this->_plugin = $plugin;
	}

	/**
	 * Get the curated default reviewer decisions supported out-of-the-box.
	 * Includes popular decisions from academic publishing (Elsevier, Springer, Wiley, IEEE).
	 *
	 * @return array
	 */
	public function getDefaultDecisions() {
		return array(
			array(
				'id' => 1,
				'key' => 'accept',
				'name' => array(
					'en_US' => 'Accept Submission',
					'ar_IQ' => 'قبول التقديم',
				),
				'description' => array(
					'en_US' => 'The submission is ready for publication as is without further scientific changes.',
					'ar_IQ' => 'التقديم جاهز للنشر كما هو دون الحاجة إلى أي تعديلات علمية إضافية.',
				),
				'color' => 'green',
				'badgeClass' => 'pkp_badge_success',
				'sequence' => 1,
				'enabled' => true,
				'isDefault' => true,
			),
			array(
				'id' => 2,
				'key' => 'minorRevisions',
				'name' => array(
					'en_US' => 'Minor Revisions Required',
					'ar_IQ' => 'تنقيحات طفيفة مطلوبة',
				),
				'description' => array(
					'en_US' => 'Requires minor textual, formatting, or bibliographical fixes. Does not require another round of external peer review.',
					'ar_IQ' => 'يتطلب تعديلات نصية أو تنسيقية أو مراجع طفيفة، ولا يتطلب جولة تحكيم خارجية ثانية.',
				),
				'color' => 'blue',
				'badgeClass' => 'pkp_badge_info',
				'sequence' => 2,
				'enabled' => true,
				'isDefault' => true,
			),
			array(
				'id' => 7,
				'key' => 'majorRevisions',
				'name' => array(
					'en_US' => 'Major Revisions Required',
					'ar_IQ' => 'تنقيحات جوهرية مطلوبة',
				),
				'description' => array(
					'en_US' => 'Significant theoretical, structural, or experimental revisions needed. Requires a subsequent round of peer review.',
					'ar_IQ' => 'يتطلب تعديلات جوهرية في المنهجية أو التحليل أو الهيكل، وتستوجب جولة تحكيم لاحقة بعد التعديل.',
				),
				'color' => 'orange',
				'badgeClass' => 'pkp_badge_warning',
				'sequence' => 3,
				'enabled' => true,
				'isDefault' => true,
			),
			array(
				'id' => 3,
				'key' => 'resubmitHere',
				'name' => array(
					'en_US' => 'Resubmit for Review',
					'ar_IQ' => 'إعادة التقديم للتحكيم',
				),
				'description' => array(
					'en_US' => 'Unacceptable in current form, but the author is invited to extensively revise and resubmit for complete re-evaluation.',
					'ar_IQ' => 'غير مقبول بصيغته الحالية، لكن يُدعى الباحث لإعادة هيكلة البحث وتقديمه من جديد لإجراء تقييم شامل.',
				),
				'color' => 'purple',
				'badgeClass' => 'pkp_badge_primary',
				'sequence' => 4,
				'enabled' => true,
				'isDefault' => true,
			),
			array(
				'id' => 4,
				'key' => 'resubmitElsewhere',
				'name' => array(
					'en_US' => 'Resubmit Elsewhere',
					'ar_IQ' => 'التقديم إلى مجلة أخرى',
				),
				'description' => array(
					'en_US' => 'Technically competent work that falls outside the specific aims and scope of this journal.',
					'ar_IQ' => 'بحث علمي جيد لكنه يقع خارج نطاق واهتمامات هذه المجلة، ويُقترح التقديم إلى مجلة أخرى متخصصة.',
				),
				'color' => 'gray',
				'badgeClass' => 'pkp_badge_secondary',
				'sequence' => 5,
				'enabled' => true,
				'isDefault' => true,
			),
			array(
				'id' => 5,
				'key' => 'decline',
				'name' => array(
					'en_US' => 'Decline Submission',
					'ar_IQ' => 'رفض التقديم',
				),
				'description' => array(
					'en_US' => 'Fundamental methodological flaws, lack of novelty/contribution, or serious issues that cannot be remedied.',
					'ar_IQ' => 'قصور جوهري في المنهجية أو ضعف الأصالة العلمية؛ المخطوطة غير مناسبة للنشر.',
				),
				'color' => 'red',
				'badgeClass' => 'pkp_badge_danger',
				'sequence' => 6,
				'enabled' => true,
				'isDefault' => true,
			),
			array(
				'id' => 6,
				'key' => 'seeComments',
				'name' => array(
					'en_US' => 'See Comments',
					'ar_IQ' => 'انظر التعليقات',
				),
				'description' => array(
					'en_US' => 'Reviewer provides detailed analytical comments for the editor\'s discretion without choosing a predefined outcome.',
					'ar_IQ' => 'يقدم المحكم تقريراً تحليلياً مفصلاً يترك تقدير القرار النهائي لرئيس وهيئة التحرير.',
				),
				'color' => 'gray',
				'badgeClass' => 'pkp_badge_secondary',
				'sequence' => 7,
				'enabled' => true,
				'isDefault' => true,
			),
			array(
				'id' => 8,
				'key' => 'conditionalAccept',
				'name' => array(
					'en_US' => 'Conditional Acceptance',
					'ar_IQ' => 'قبول مشروط',
				),
				'description' => array(
					'en_US' => 'Acceptance is contingent upon verification of data/code repository availability or compliance checks.',
					'ar_IQ' => 'القبول معلق ومشروط بالتحقق من إيداع البيانات أو الأكواد البرمجية أو الموافقات الرسمية.',
				),
				'color' => 'teal',
				'badgeClass' => 'pkp_badge_info',
				'sequence' => 8,
				'enabled' => true,
				'isDefault' => true,
			),
			array(
				'id' => 9,
				'key' => 'rejectScope',
				'name' => array(
					'en_US' => 'Reject - Out of Scope',
					'ar_IQ' => 'رفض - خارج نطاق المجلة',
				),
				'description' => array(
					'en_US' => 'Manuscript topic does not match any focus areas or publication scope of the journal.',
					'ar_IQ' => 'موضوع البحث لا يتطابق إطلاقاً مع المجالات البحثية المعتمدة للمجلة.',
				),
				'color' => 'darkred',
				'badgeClass' => 'pkp_badge_danger',
				'sequence' => 9,
				'enabled' => false,
				'isDefault' => true,
			),
		);
	}

	/**
	 * Get all decisions configured for a context.
	 *
	 * @param int $contextId
	 * @return array
	 */
	public function getDecisions($contextId) {
		$contextId = (int) $contextId;
		$decisions = $this->_plugin->getSetting($contextId, 'reviewerDecisions');

		if (!is_array($decisions) || empty($decisions)) {
			$decisions = $this->getDefaultDecisions();
			// Cache defaults to settings on first access
			$this->_plugin->updateSetting($contextId, 'reviewerDecisions', $decisions);
		}

		// Sort decisions by sequence
		usort($decisions, function($a, $b) {
			$seqA = isset($a['sequence']) ? (int) $a['sequence'] : 0;
			$seqB = isset($b['sequence']) ? (int) $b['sequence'] : 0;
			if ($seqA === $seqB) {
				return ((int) $a['id']) - ((int) $b['id']);
			}
			return $seqA - $seqB;
		});

		return $decisions;
	}

	/**
	 * Save the entire decisions array for a context.
	 *
	 * @param int $contextId
	 * @param array $decisions
	 * @return bool
	 */
	public function saveDecisions($contextId, $decisions) {
		$contextId = (int) $contextId;
		return $this->_plugin->updateSetting($contextId, 'reviewerDecisions', array_values($decisions));
	}

	/**
	 * Find a single decision by its ID.
	 *
	 * @param int $contextId
	 * @param int $id
	 * @return array|null
	 */
	public function getDecisionById($contextId, $id) {
		$decisions = $this->getDecisions($contextId);
		$id = (int) $id;

		foreach ($decisions as $decision) {
			if ((int) $decision['id'] === $id) {
				return $decision;
			}
		}
		return null;
	}

	/**
	 * Add or update a decision.
	 *
	 * @param int $contextId
	 * @param array $data
	 * @return array Saved decision
	 */
	public function saveDecision($contextId, $data) {
		$decisions = $this->getDecisions($contextId);
		$id = isset($data['id']) && (int) $data['id'] > 0 ? (int) $data['id'] : $this->getNextAvailableId($contextId);

		$existingIndex = -1;
		foreach ($decisions as $idx => $d) {
			if ((int) $d['id'] === $id) {
				$existingIndex = $idx;
				break;
			}
		}

		$color = !empty($data['color']) ? sanitize_text_field($data['color']) : 'blue';
		$badgeClass = $this->mapColorToBadgeClass($color);

		$decisionRecord = array(
			'id' => $id,
			'key' => !empty($data['key']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $data['key']) : 'custom_' . $id,
			'name' => is_array($data['name']) ? $data['name'] : array('en_US' => (string) $data['name']),
			'description' => is_array($data['description']) ? $data['description'] : array('en_US' => (string) $data['description']),
			'color' => $color,
			'badgeClass' => $badgeClass,
			'sequence' => isset($data['sequence']) ? (int) $data['sequence'] : (count($decisions) + 1),
			'enabled' => isset($data['enabled']) ? (bool) $data['enabled'] : true,
			'isDefault' => $existingIndex >= 0 && !empty($decisions[$existingIndex]['isDefault']),
		);

		if ($existingIndex >= 0) {
			$decisions[$existingIndex] = $decisionRecord;
		} else {
			$decisions[] = $decisionRecord;
		}

		$this->saveDecisions($contextId, $decisions);
		return $decisionRecord;
	}

	/**
	 * Delete a custom decision (built-in default decisions can only be disabled, not deleted).
	 *
	 * @param int $contextId
	 * @param int $id
	 * @return bool
	 */
	public function deleteDecision($contextId, $id) {
		$decisions = $this->getDecisions($contextId);
		$id = (int) $id;
		$newDecisions = array();

		foreach ($decisions as $d) {
			if ((int) $d['id'] === $id) {
				if (!empty($d['isDefault'])) {
					// Default decisions cannot be deleted; disable instead
					$d['enabled'] = false;
					$newDecisions[] = $d;
				}
				// Custom decision: omit it from array (delete)
			} else {
				$newDecisions[] = $d;
			}
		}

		return $this->saveDecisions($contextId, $newDecisions);
	}

	/**
	 * Toggle the enabled status of a decision.
	 *
	 * @param int $contextId
	 * @param int $id
	 * @return bool New enabled status
	 */
	public function toggleDecision($contextId, $id) {
		$decisions = $this->getDecisions($contextId);
		$id = (int) $id;
		$newStatus = false;

		foreach ($decisions as &$d) {
			if ((int) $d['id'] === $id) {
				$d['enabled'] = !empty($d['enabled']) ? false : true;
				$newStatus = $d['enabled'];
				break;
			}
		}

		$this->saveDecisions($contextId, $decisions);
		return $newStatus;
	}

	/**
	 * Reorder decisions according to an ordered list of IDs.
	 *
	 * @param int $contextId
	 * @param array $orderedIds
	 * @return bool
	 */
	public function reorderDecisions($contextId, $orderedIds) {
		$decisions = $this->getDecisions($contextId);
		$indexed = array();
		foreach ($decisions as $d) {
			$indexed[(int) $d['id']] = $d;
		}

		$reordered = array();
		$seq = 1;
		foreach ($orderedIds as $id) {
			$id = (int) $id;
			if (isset($indexed[$id])) {
				$indexed[$id]['sequence'] = $seq++;
				$reordered[] = $indexed[$id];
				unset($indexed[$id]);
			}
		}

		// Append any remaining
		foreach ($indexed as $remaining) {
			$remaining['sequence'] = $seq++;
			$reordered[] = $remaining;
		}

		return $this->saveDecisions($contextId, $reordered);
	}

	/**
	 * Reset all decisions to system defaults.
	 *
	 * @param int $contextId
	 * @return bool
	 */
	public function resetToDefaults($contextId) {
		$defaults = $this->getDefaultDecisions();
		return $this->saveDecisions($contextId, $defaults);
	}

	/**
	 * Get the localized associative options array ready for Smarty select element.
	 * Format: [ '' => 'Choose One...', 1 => 'Accept Submission', ... ]
	 *
	 * @param int $contextId
	 * @param string|null $locale
	 * @param bool $includeDisabled
	 * @return array
	 */
	public function getLocalizedOptions($contextId, $locale = null, $includeDisabled = false) {
		$decisions = $this->getDecisions($contextId);
		$options = array(
			'' => __('common.chooseOne')
		);

		foreach ($decisions as $decision) {
			if (!$includeDisabled && empty($decision['enabled'])) {
				continue;
			}
			$title = $this->getLocalizedTitle($decision, $locale);
			$options[(int) $decision['id']] = $title;
		}

		return $options;
	}

	/**
	 * Get localized title for a decision.
	 * Falls back gracefully to primary locale, en_US, or decision key.
	 *
	 * @param array $decision
	 * @param string|null $locale
	 * @return string
	 */
	public function getLocalizedTitle($decision, $locale = null) {
		if (empty($locale)) {
			$locale = AppLocale::getLocale();
		}

		if (is_array($decision['name'])) {
			if (!empty($decision['name'][$locale])) {
				return $decision['name'][$locale];
			}
			// Fallback check for general language code (e.g. 'ar' vs 'ar_IQ')
			$langPrefix = substr($locale, 0, 2);
			foreach ($decision['name'] as $k => $val) {
				if (substr($k, 0, 2) === $langPrefix && !empty($val)) {
					return $val;
				}
			}
			// Fallback to en_US
			if (!empty($decision['name']['en_US'])) {
				return $decision['name']['en_US'];
			}
			// First available non-empty
			foreach ($decision['name'] as $val) {
				if (!empty($val)) return $val;
			}
		}

		return !empty($decision['key']) ? $decision['key'] : 'Decision #' . $decision['id'];
	}

	/**
	 * Get localized description / reviewer guidelines for a decision.
	 *
	 * @param array $decision
	 * @param string|null $locale
	 * @return string
	 */
	public function getLocalizedDescription($decision, $locale = null) {
		if (empty($locale)) {
			$locale = AppLocale::getLocale();
		}

		if (is_array($decision['description'])) {
			if (!empty($decision['description'][$locale])) {
				return $decision['description'][$locale];
			}
			$langPrefix = substr($locale, 0, 2);
			foreach ($decision['description'] as $k => $val) {
				if (substr($k, 0, 2) === $langPrefix && !empty($val)) {
					return $val;
				}
			}
			if (!empty($decision['description']['en_US'])) {
				return $decision['description']['en_US'];
			}
			foreach ($decision['description'] as $val) {
				if (!empty($val)) return $val;
			}
		}

		return '';
	}

	/**
	 * Get a JSON-serializable metadata map of all decisions for frontend JS.
	 * Keyed by decision ID.
	 *
	 * @param int $contextId
	 * @param string|null $locale
	 * @return array
	 */
	public function getMetadataMap($contextId, $locale = null) {
		$decisions = $this->getDecisions($contextId);
		$map = array();

		foreach ($decisions as $decision) {
			$id = (int) $decision['id'];
			$map[$id] = array(
				'id' => $id,
				'key' => $decision['key'],
				'title' => $this->getLocalizedTitle($decision, $locale),
				'description' => $this->getLocalizedDescription($decision, $locale),
				'color' => !empty($decision['color']) ? $decision['color'] : 'blue',
				'badgeClass' => !empty($decision['badgeClass']) ? $decision['badgeClass'] : 'pkp_badge_info',
				'enabled' => !empty($decision['enabled']),
			);
		}

		return $map;
	}

	/**
	 * Generate next available integer ID for a new custom decision.
	 * Ensures no collisions with default IDs (1-9) or already added IDs.
	 *
	 * @param int $contextId
	 * @return int
	 */
	public function getNextAvailableId($contextId) {
		$decisions = $this->getDecisions($contextId);
		$maxId = 9; // Core / default range is 1-9

		foreach ($decisions as $d) {
			$id = (int) $d['id'];
			if ($id > $maxId) {
				$maxId = $id;
			}
		}

		return $maxId + 1;
	}

	/**
	 * Map semantic color string to CSS badge class.
	 *
	 * @param string $color
	 * @return string
	 */
	public function mapColorToBadgeClass($color) {
		switch (strtolower($color)) {
			case 'green':
			case 'success':
				return 'pkp_badge_success';
			case 'blue':
			case 'info':
				return 'pkp_badge_info';
			case 'orange':
			case 'amber':
			case 'warning':
				return 'pkp_badge_warning';
			case 'red':
			case 'danger':
				return 'pkp_badge_danger';
			case 'darkred':
				return 'pkp_badge_darkred';
			case 'purple':
			case 'primary':
				return 'pkp_badge_primary';
			case 'teal':
			case 'cyan':
				return 'pkp_badge_teal';
			case 'gray':
			case 'secondary':
			default:
				return 'pkp_badge_secondary';
		}
	}
}

if (!function_exists('sanitize_text_field')) {
	function sanitize_text_field($str) {
		return strip_tags(trim($str));
	}
}
