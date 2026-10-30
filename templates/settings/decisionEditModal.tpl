{**
 * plugins/generic/customReviewerDecisionsPlugin/templates/settings/decisionEditModal.tpl
 *
 * Copyright (c) 2026
 * Distributed under the GNU GPL v3.
 *
 * Modal for creating and editing reviewer decisions with full multilingual support.
 *}

<div class="pkp_crd_modal_wrapper">
	<form id="crdDecisionEditForm" method="post" action="{url op="manage" category="generic" plugin=$pluginName verb="saveDecision"}">
		{csrf}
		<input type="hidden" name="id" value="{$decision.id|escape}" />

		{* Modal Header Info *}
		<div class="pkp_crd_modal_header">
			<h4>
				{if $isNew}
					<span class="fa fa-plus-circle"></span> {translate key="plugins.generic.customReviewerDecisionsPlugin.modal.addTitle"}
				{else}
					<span class="fa fa-edit"></span> {translate key="plugins.generic.customReviewerDecisionsPlugin.modal.editTitle" id=$decision.id}
				{/if}
			</h4>
			<p class="description">{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.subtitle"}</p>
		</div>

		{* Top Row: Core Metadata *}
		<div class="pkp_crd_form_row pkp_crd_two_col">
			<div class="pkp_crd_field_group">
				<label for="crdKey">
					<strong>{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.key"}</strong>
					<span class="pkp_crd_help">{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.key.desc"}</span>
				</label>
				<input type="text" id="crdKey" name="key" value="{$decision.key|escape}" class="pkp_input" required="required" placeholder="e.g. majorRevisions" />
			</div>

			<div class="pkp_crd_field_group">
				<label for="crdSequence">
					<strong>{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.sequence"}</strong>
					<span class="pkp_crd_help">{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.sequence.desc"}</span>
				</label>
				<input type="number" id="crdSequence" name="sequence" value="{$decision.sequence|default:1}" min="1" max="99" class="pkp_input" style="max-width: 120px;" />
			</div>
		</div>

		{* Color / Badge Selector *}
		<div class="pkp_crd_form_row">
			<label>
				<strong>{translate key="plugins.generic.customReviewerDecisionsPlugin.color"}</strong>
				<span class="pkp_crd_help">{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.color.desc"}</span>
			</label>
			<div class="pkp_crd_color_picker">
				{foreach from=$colorOptions key=colorKey item=colorLabel}
					<label class="pkp_crd_color_option {if $decision.color == $colorKey}selected{/if}">
						<input type="radio" name="color" value="{$colorKey|escape}" {if $decision.color == $colorKey}checked="checked"{/if} />
						<span class="pkp_crd_dot pkp_crd_dot_{$colorKey|escape}"></span>
						<span class="pkp_crd_color_name">{$colorLabel|escape}</span>
					</label>
				{/foreach}
			</div>
		</div>

		{* Multilingual Section *}
		<div class="pkp_crd_multilingual_box">
			<div class="pkp_crd_locales_header">
				<strong>{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.multilingualContent"}</strong>
				<span class="pkp_crd_help">{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.multilingualHelp"}</span>
			</div>

			{* Locale Tabs *}
			<div class="pkp_crd_tabs_nav">
				{foreach from=$supportedLocales item=locKey name=locLoop}
					<button type="button" class="pkp_crd_tab_btn {if $locKey == $currentLocale || ($smarty.foreach.locLoop.first && !in_array($currentLocale, $supportedLocales))}active{/if}" data-target="crd_locale_tab_{$locKey}">
						<span class="pkp_crd_tab_lang_code">{$locKey|substr:0:2|upper}</span>
						{$localeNames.$locKey|escape}
						{if $locKey == $primaryLocale}
							<span class="pkp_crd_primary_badge">({translate key="admin.settings.primaryLocale"})</span>
						{/if}
					</button>
				{/foreach}
			</div>

			{* Locale Tab Panes *}
			<div class="pkp_crd_tabs_content">
				{foreach from=$supportedLocales item=locKey name=locLoop2}
					<div class="pkp_crd_tab_pane {if $locKey == $currentLocale || ($smarty.foreach.locLoop2.first && !in_array($currentLocale, $supportedLocales))}active{/if}" id="crd_locale_tab_{$locKey}">
						<div class="pkp_crd_field_group" style="margin-bottom: 14px;">
							<label for="crdName_{$locKey}">
								<strong>{translate key="plugins.generic.customReviewerDecisionsPlugin.decisionTitle"} ({$localeNames.$locKey|escape})</strong>
								{if $locKey == $primaryLocale}<span class="pkp_crd_required">*</span>{/if}
							</label>
							<input type="text" id="crdName_{$locKey}" name="name[{$locKey}]" value="{if isset($decision.name.$locKey)}{$decision.name.$locKey|escape}{/if}" class="pkp_input" style="width: 100%;" {if $locKey == $primaryLocale}required="required"{/if} placeholder="{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.titlePlaceholder"}" />
						</div>

						<div class="pkp_crd_field_group">
							<label for="crdDesc_{$locKey}">
								<strong>{translate key="plugins.generic.customReviewerDecisionsPlugin.description"} / {translate key="plugins.generic.customReviewerDecisionsPlugin.reviewerGuidelines"} ({$localeNames.$locKey|escape})</strong>
							</label>
							<textarea id="crdDesc_{$locKey}" name="description[{$locKey}]" rows="3" class="pkp_input" style="width: 100%; resize: vertical;" placeholder="{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.descPlaceholder"}">{if isset($decision.description.$locKey)}{$decision.description.$locKey|escape}{/if}</textarea>
						</div>
					</div>
				{/foreach}
			</div>
		</div>

		{* Status Checkbox *}
		<div class="pkp_crd_form_row" style="margin-top: 18px;">
			<label class="pkp_crd_checkbox_label">
				<input type="hidden" name="enabled" value="0" />
				<input type="checkbox" name="enabled" value="1" {if $decision.enabled}checked="checked"{/if} />
				<div>
					<strong>{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.enableDecision"}</strong>
					<span class="pkp_crd_subtext">{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.enableDecision.desc"}</span>
				</div>
			</label>
		</div>

		{* Modal Footer Buttons *}
		<div class="pkp_crd_modal_footer">
			<button type="button" class="pkp_button pkp_button_secondary pkp_modal_close_btn">
				{translate key="common.cancel"}
			</button>
			<button type="submit" id="crdSaveDecisionBtn" class="pkp_button pkp_button_primary">
				<span class="fa fa-check"></span> {translate key="common.save"}
			</button>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		if (typeof CustomReviewerDecisionsAdmin !== 'undefined') {
			CustomReviewerDecisionsAdmin.initModal();
		}
	});
</script>
