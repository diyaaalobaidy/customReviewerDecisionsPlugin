{**
 * plugins/generic/customReviewerDecisionsPlugin/templates/review/decisionGuidelines.tpl
 *
 * Copyright (c) 2026
 * Distributed under the GNU GPL v3.
 *
 * Dynamic reviewer decision guidelines box shown on Step 3 of the review.
 *}

<div id="crdReviewerGuidelinesBox" class="pkp_crd_guidelines_box" style="display: none;">
	<div class="pkp_crd_guidelines_header">
		<span class="fa fa-lightbulb-o pkp_crd_guidelines_icon"></span>
		<strong id="crdGuidelinesTitle">{translate key="plugins.generic.customReviewerDecisionsPlugin.reviewerGuidelines"}</strong>
		<span id="crdGuidelinesBadge" class="pkp_crd_badge" style="display: none;"></span>
	</div>
	<div id="crdGuidelinesContent" class="pkp_crd_guidelines_content">
		{* Dynamically populated by JS *}
	</div>
</div>
