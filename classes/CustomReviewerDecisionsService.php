<?php

/**
 * @file plugins/generic/customReviewerDecisionsPlugin/classes/CustomReviewerDecisionsService.php
 *
 * Copyright (c) 2026
 * Distributed under the GNU GPL v3.
 *
 * @class CustomReviewerDecisionsService
 * @ingroup plugins_generic_customReviewerDecisionsPlugin
 *
 * @brief Service class for managing custom reviewer decisions, localization, and defaults in OJS 3.5.
 */

namespace APP\plugins\generic\customReviewerDecisionsPlugin\classes;

use APP\plugins\generic\customReviewerDecisionsPlugin\CustomReviewerDecisionsPlugin;
use PKP\facades\Locale;

class CustomReviewerDecisionsService
{
    /** @var CustomReviewerDecisionsPlugin */
    protected $_plugin;

    /**
     * Constructor
     */
    public function __construct(CustomReviewerDecisionsPlugin $plugin)
    {
        $this->_plugin = $plugin;
    }

    /**
     * Get the curated default reviewer decisions supported out-of-the-box.
     * Includes popular decisions from academic publishing (Elsevier, Springer, Wiley, IEEE).
     */
    public function getDefaultDecisions(): array
    {
        return [
            [
                'id' => 1,
                'key' => 'accept',
                'name' => [
                    'en' => 'Accept Submission',
                    'en_US' => 'Accept Submission',
                    'ar' => 'قبول التقديم',
                    'ar_IQ' => 'قبول التقديم',
                ],
                'description' => [
                    'en' => 'The submission is ready for publication as is without further scientific changes.',
                    'en_US' => 'The submission is ready for publication as is without further scientific changes.',
                    'ar' => 'التقديم جاهز للنشر كما هو دون الحاجة إلى أي تعديلات علمية إضافية.',
                    'ar_IQ' => 'التقديم جاهز للنشر كما هو دون الحاجة إلى أي تعديلات علمية إضافية.',
                ],
                'color' => 'green',
                'badgeClass' => 'pkp_badge_success',
                'sequence' => 1,
                'enabled' => true,
                'isDefault' => true,
            ],
            [
                'id' => 2,
                'key' => 'minorRevisions',
                'name' => [
                    'en' => 'Minor Revisions Required',
                    'en_US' => 'Minor Revisions Required',
                    'ar' => 'تنقيحات طفيفة مطلوبة',
                    'ar_IQ' => 'تنقيحات طفيفة مطلوبة',
                ],
                'description' => [
                    'en' => 'Requires minor textual, formatting, or bibliographical fixes. Does not require another round of external peer review.',
                    'en_US' => 'Requires minor textual, formatting, or bibliographical fixes. Does not require another round of external peer review.',
                    'ar' => 'يتطلب تعديلات نصية أو تنسيقية أو مراجع طفيفة، ولا يتطلب جولة تحكيم خارجية ثانية.',
                    'ar_IQ' => 'يتطلب تعديلات نصية أو تنسيقية أو مراجع طفيفة، ولا يتطلب جولة تحكيم خارجية ثانية.',
                ],
                'color' => 'blue',
                'badgeClass' => 'pkp_badge_info',
                'sequence' => 2,
                'enabled' => true,
                'isDefault' => true,
            ],
            [
                'id' => 7,
                'key' => 'majorRevisions',
                'name' => [
                    'en' => 'Major Revisions Required',
                    'en_US' => 'Major Revisions Required',
                    'ar' => 'تنقيحات جوهرية مطلوبة',
                    'ar_IQ' => 'تنقيحات جوهرية مطلوبة',
                ],
                'description' => [
                    'en' => 'Significant theoretical, structural, or experimental revisions needed. Requires a subsequent round of peer review.',
                    'en_US' => 'Significant theoretical, structural, or experimental revisions needed. Requires a subsequent round of peer review.',
                    'ar' => 'يتطلب تعديلات جوهرية في المنهجية أو التحليل أو الهيكل، وتستوجب جولة تحكيم لاحقة بعد التعديل.',
                    'ar_IQ' => 'يتطلب تعديلات جوهرية في المنهجية أو التحليل أو الهيكل، وتستوجب جولة تحكيم لاحقة بعد التعديل.',
                ],
                'color' => 'orange',
                'badgeClass' => 'pkp_badge_warning',
                'sequence' => 3,
                'enabled' => true,
                'isDefault' => true,
            ],
            [
                'id' => 3,
                'key' => 'resubmitHere',
                'name' => [
                    'en' => 'Resubmit for Review',
                    'en_US' => 'Resubmit for Review',
                    'ar' => 'إعادة التقديم للتحكيم',
                    'ar_IQ' => 'إعادة التقديم للتحكيم',
                ],
                'description' => [
                    'en' => 'Unacceptable in current form, but the author is invited to extensively revise and resubmit for complete re-evaluation.',
                    'en_US' => 'Unacceptable in current form, but the author is invited to extensively revise and resubmit for complete re-evaluation.',
                    'ar' => 'غير مقبول بصيغته الحالية، لكن يُدعى الباحث لإعادة هيكلة البحث وتقديمه من جديد لإجراء تقييم شامل.',
                    'ar_IQ' => 'غير مقبول بصيغته الحالية، لكن يُدعى الباحث لإعادة هيكلة البحث وتقديمه من جديد لإجراء تقييم شامل.',
                ],
                'color' => 'purple',
                'badgeClass' => 'pkp_badge_primary',
                'sequence' => 4,
                'enabled' => true,
                'isDefault' => true,
            ],
            [
                'id' => 4,
                'key' => 'resubmitElsewhere',
                'name' => [
                    'en' => 'Resubmit Elsewhere',
                    'en_US' => 'Resubmit Elsewhere',
                    'ar' => 'التقديم إلى مجلة أخرى',
                    'ar_IQ' => 'التقديم إلى مجلة أخرى',
                ],
                'description' => [
                    'en' => 'Technically competent work that falls outside the specific aims and scope of this journal.',
                    'en_US' => 'Technically competent work that falls outside the specific aims and scope of this journal.',
                    'ar' => 'بحث علمي جيد لكنه يقع خارج نطاق واهتمامات هذه المجلة، ويُقترح التقديم إلى مجلة أخرى متخصصة.',
                    'ar_IQ' => 'بحث علمي جيد لكنه يقع خارج نطاق واهتمامات هذه المجلة، ويُقترح التقديم إلى مجلة أخرى متخصصة.',
                ],
                'color' => 'gray',
                'badgeClass' => 'pkp_badge_secondary',
                'sequence' => 5,
                'enabled' => true,
                'isDefault' => true,
            ],
            [
                'id' => 5,
                'key' => 'decline',
                'name' => [
                    'en' => 'Decline Submission',
                    'en_US' => 'Decline Submission',
                    'ar' => 'رفض التقديم',
                    'ar_IQ' => 'رفض التقديم',
                ],
                'description' => [
                    'en' => 'Fundamental methodological flaws, lack of novelty/contribution, or serious issues that cannot be remedied.',
                    'en_US' => 'Fundamental methodological flaws, lack of novelty/contribution, or serious issues that cannot be remedied.',
                    'ar' => 'قصور جوهري في المنهجية أو ضعف الأصالة العلمية؛ المخطوطة غير مناسبة للنشر.',
                    'ar_IQ' => 'قصور جوهري في المنهجية أو ضعف الأصالة العلمية؛ المخطوطة غير مناسبة للنشر.',
                ],
                'color' => 'red',
                'badgeClass' => 'pkp_badge_danger',
                'sequence' => 6,
                'enabled' => true,
                'isDefault' => true,
            ],
            [
                'id' => 6,
                'key' => 'seeComments',
                'name' => [
                    'en' => 'See Comments',
                    'en_US' => 'See Comments',
                    'ar' => 'انظر التعليقات',
                    'ar_IQ' => 'انظر التعليقات',
                ],
                'description' => [
                    'en' => 'Reviewer provides detailed analytical comments for the editor\'s discretion without choosing a predefined outcome.',
                    'en_US' => 'Reviewer provides detailed analytical comments for the editor\'s discretion without choosing a predefined outcome.',
                    'ar' => 'يقدم المحكم تقريراً تحليلياً مفصلاً يترك تقدير القرار النهائي لرئيس وهيئة التحرير.',
                    'ar_IQ' => 'يقدم المحكم تقريراً تحليلياً مفصلاً يترك تقدير القرار النهائي لرئيس وهيئة التحرير.',
                ],
                'color' => 'gray',
                'badgeClass' => 'pkp_badge_secondary',
                'sequence' => 7,
                'enabled' => true,
                'isDefault' => true,
            ],
            [
                'id' => 8,
                'key' => 'conditionalAccept',
                'name' => [
                    'en' => 'Conditional Acceptance',
                    'en_US' => 'Conditional Acceptance',
                    'ar' => 'قبول مشروط',
                    'ar_IQ' => 'قبول مشروط',
                ],
                'description' => [
                    'en' => 'Acceptance is contingent upon verification of data/code repository availability or compliance checks.',
                    'en_US' => 'Acceptance is contingent upon verification of data/code repository availability or compliance checks.',
                    'ar' => 'القبول معلق ومشروط بالتحقق من إيداع البيانات أو الأكواد البرمجية أو الموافقات الرسمية.',
                    'ar_IQ' => 'القبول معلق ومشروط بالتحقق من إيداع البيانات أو الأكواد البرمجية أو الموافقات الرسمية.',
                ],
                'color' => 'teal',
                'badgeClass' => 'pkp_badge_info',
                'sequence' => 8,
                'enabled' => true,
                'isDefault' => true,
            ],
            [
                'id' => 9,
                'key' => 'rejectScope',
                'name' => [
                    'en' => 'Reject - Out of Scope',
                    'en_US' => 'Reject - Out of Scope',
                    'ar' => 'رفض - خارج نطاق المجلة',
                    'ar_IQ' => 'رفض - خارج نطاق المجلة',
                ],
                'description' => [
                    'en' => 'Manuscript topic does not match any focus areas or publication scope of the journal.',
                    'en_US' => 'Manuscript topic does not match any focus areas or publication scope of the journal.',
                    'ar' => 'موضوع البحث لا يتطابق إطلاقاً مع المجالات البحثية المعتمدة للمجلة.',
                    'ar_IQ' => 'موضوع البحث لا يتطابق إطلاقاً مع المجالات البحثية المعتمدة للمجلة.',
                ],
                'color' => 'darkred',
                'badgeClass' => 'pkp_badge_danger',
                'sequence' => 9,
                'enabled' => false,
                'isDefault' => true,
            ],
        ];
    }

    /**
     * Get all decisions configured for a context.
     */
    public function getDecisions(int $contextId): array
    {
        $decisions = $this->_plugin->getSetting($contextId, 'reviewerDecisions');

        if (!is_array($decisions) || empty($decisions)) {
            $decisions = $this->getDefaultDecisions();
            $this->_plugin->updateSetting($contextId, 'reviewerDecisions', $decisions);
        }

        usort($decisions, function ($a, $b) {
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
     * Save decisions array for a context.
     */
    public function saveDecisions(int $contextId, array $decisions): bool
    {
        return (bool) $this->_plugin->updateSetting($contextId, 'reviewerDecisions', array_values($decisions));
    }

    /**
     * Find a decision by ID.
     */
    public function getDecisionById(int $contextId, int $id): ?array
    {
        $decisions = $this->getDecisions($contextId);
        foreach ($decisions as $decision) {
            if ((int) $decision['id'] === $id) {
                return $decision;
            }
        }
        return null;
    }

    /**
     * Add or update a decision.
     */
    public function saveDecision(int $contextId, array $data): array
    {
        $decisions = $this->getDecisions($contextId);
        $id = isset($data['id']) && (int) $data['id'] > 0 ? (int) $data['id'] : $this->getNextAvailableId($contextId);

        $existingIndex = -1;
        foreach ($decisions as $idx => $d) {
            if ((int) $d['id'] === $id) {
                $existingIndex = $idx;
                break;
            }
        }

        $color = !empty($data['color']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $data['color']) : 'blue';
        $badgeClass = $this->mapColorToBadgeClass($color);

        $decisionRecord = [
            'id' => $id,
            'key' => !empty($data['key']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $data['key']) : 'custom_' . $id,
            'name' => is_array($data['name']) ? $data['name'] : ['en' => (string) $data['name']],
            'description' => is_array($data['description']) ? $data['description'] : ['en' => (string) $data['description']],
            'color' => $color,
            'badgeClass' => $badgeClass,
            'sequence' => isset($data['sequence']) ? (int) $data['sequence'] : (count($decisions) + 1),
            'enabled' => isset($data['enabled']) ? (bool) $data['enabled'] : true,
            'isDefault' => $existingIndex >= 0 && !empty($decisions[$existingIndex]['isDefault']),
        ];

        if ($existingIndex >= 0) {
            $decisions[$existingIndex] = $decisionRecord;
        } else {
            $decisions[] = $decisionRecord;
        }

        $this->saveDecisions($contextId, $decisions);
        return $decisionRecord;
    }

    /**
     * Delete custom decision.
     */
    public function deleteDecision(int $contextId, int $id): bool
    {
        $decisions = $this->getDecisions($contextId);
        $newDecisions = [];

        foreach ($decisions as $d) {
            if ((int) $d['id'] === $id) {
                if (!empty($d['isDefault'])) {
                    $d['enabled'] = false;
                    $newDecisions[] = $d;
                }
            } else {
                $newDecisions[] = $d;
            }
        }

        return $this->saveDecisions($contextId, $newDecisions);
    }

    /**
     * Toggle decision active state.
     */
    public function toggleDecision(int $contextId, int $id): bool
    {
        $decisions = $this->getDecisions($contextId);
        $newStatus = false;

        foreach ($decisions as &$d) {
            if ((int) $d['id'] === $id) {
                $d['enabled'] = empty($d['enabled']);
                $newStatus = $d['enabled'];
                break;
            }
        }

        $this->saveDecisions($contextId, $decisions);
        return $newStatus;
    }

    /**
     * Reorder decisions by IDs.
     */
    public function reorderDecisions(int $contextId, array $orderedIds): bool
    {
        $decisions = $this->getDecisions($contextId);
        $indexed = [];
        foreach ($decisions as $d) {
            $indexed[(int) $d['id']] = $d;
        }

        $reordered = [];
        $seq = 1;
        foreach ($orderedIds as $id) {
            $id = (int) $id;
            if (isset($indexed[$id])) {
                $indexed[$id]['sequence'] = $seq++;
                $reordered[] = $indexed[$id];
                unset($indexed[$id]);
            }
        }

        foreach ($indexed as $remaining) {
            $remaining['sequence'] = $seq++;
            $reordered[] = $remaining;
        }

        return $this->saveDecisions($contextId, $reordered);
    }

    /**
     * Reset decisions to system defaults.
     */
    public function resetToDefaults(int $contextId): bool
    {
        $defaults = $this->getDefaultDecisions();
        return $this->saveDecisions($contextId, $defaults);
    }

    /**
     * Get localized associative options array for select element.
     */
    public function getLocalizedOptions(int $contextId, ?string $locale = null, bool $includeDisabled = false): array
    {
        $decisions = $this->getDecisions($contextId);
        $options = [
            '' => __('common.chooseOne')
        ];

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
     * Get localized title.
     */
    public function getLocalizedTitle(array $decision, ?string $locale = null): string
    {
        if (empty($locale)) {
            $locale = class_exists(Locale::class) ? Locale::getLocale() : 'en';
        }

        if (is_array($decision['name'])) {
            if (!empty($decision['name'][$locale])) {
                return $decision['name'][$locale];
            }
            $prefix = substr($locale, 0, 2);
            foreach ($decision['name'] as $k => $val) {
                if (substr($k, 0, 2) === $prefix && !empty($val)) {
                    return $val;
                }
            }
            if (!empty($decision['name']['en'])) {
                return $decision['name']['en'];
            }
            if (!empty($decision['name']['en_US'])) {
                return $decision['name']['en_US'];
            }
            foreach ($decision['name'] as $val) {
                if (!empty($val)) return $val;
            }
        }

        return !empty($decision['key']) ? $decision['key'] : 'Decision #' . $decision['id'];
    }

    /**
     * Get localized description / guidelines.
     */
    public function getLocalizedDescription(array $decision, ?string $locale = null): string
    {
        if (empty($locale)) {
            $locale = class_exists(Locale::class) ? Locale::getLocale() : 'en';
        }

        if (is_array($decision['description'])) {
            if (!empty($decision['description'][$locale])) {
                return $decision['description'][$locale];
            }
            $prefix = substr($locale, 0, 2);
            foreach ($decision['description'] as $k => $val) {
                if (substr($k, 0, 2) === $prefix && !empty($val)) {
                    return $val;
                }
            }
            if (!empty($decision['description']['en'])) {
                return $decision['description']['en'];
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
     * Metadata map for frontend JS.
     */
    public function getMetadataMap(int $contextId, ?string $locale = null): array
    {
        $decisions = $this->getDecisions($contextId);
        $map = [];

        foreach ($decisions as $decision) {
            $id = (int) $decision['id'];
            $map[$id] = [
                'id' => $id,
                'key' => $decision['key'],
                'title' => $this->getLocalizedTitle($decision, $locale),
                'description' => $this->getLocalizedDescription($decision, $locale),
                'color' => !empty($decision['color']) ? $decision['color'] : 'blue',
                'badgeClass' => !empty($decision['badgeClass']) ? $decision['badgeClass'] : 'pkp_badge_info',
                'enabled' => !empty($decision['enabled']),
            ];
        }

        return $map;
    }

    /**
     * Get next available integer ID.
     */
    public function getNextAvailableId(int $contextId): int
    {
        $decisions = $this->getDecisions($contextId);
        $maxId = 9;

        foreach ($decisions as $d) {
            $id = (int) $d['id'];
            if ($id > $maxId) {
                $maxId = $id;
            }
        }

        return $maxId + 1;
    }

    /**
     * Map color string to badge class.
     */
    public function mapColorToBadgeClass(string $color): string
    {
        return match (strtolower($color)) {
            'green', 'success' => 'pkp_badge_success',
            'blue', 'info' => 'pkp_badge_info',
            'orange', 'amber', 'warning' => 'pkp_badge_warning',
            'red', 'danger' => 'pkp_badge_danger',
            'darkred' => 'pkp_badge_darkred',
            'purple', 'primary' => 'pkp_badge_primary',
            'teal', 'cyan' => 'pkp_badge_teal',
            default => 'pkp_badge_secondary',
        };
    }
}
