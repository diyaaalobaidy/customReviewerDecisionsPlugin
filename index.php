<?php

/**
 * @file plugins/generic/customReviewerDecisionsPlugin/index.php
 *
 * Copyright (c) 2026
 * Distributed under the GNU GPL v3.
 *
 * @brief Wrapper for the Custom Reviewer Decisions Plugin for OJS 3.5.
 */

require_once __DIR__ . '/CustomReviewerDecisionsPlugin.php';

return new \APP\plugins\generic\customReviewerDecisionsPlugin\CustomReviewerDecisionsPlugin();
