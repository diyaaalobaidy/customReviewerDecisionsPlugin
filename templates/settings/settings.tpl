{**
 * plugins/generic/customReviewerDecisionsPlugin/templates/settings/settings.tpl
 *
 * Copyright (c) 2026
 * Distributed under the GNU GPL v3.
 *
 * Custom Reviewer Decisions Plugin - Journal Manager Management Dashboard.
 *}

<div class="pkp_crd_wrapper">
	{* Header Banner *}
	<div class="pkp_crd_header">
		<div class="pkp_crd_title_area">
			<h3>{translate key="plugins.generic.customReviewerDecisionsPlugin.settings.title"}</h3>
			<p class="description">{translate key="plugins.generic.customReviewerDecisionsPlugin.settings.description"}</p>
		</div>
		<div class="pkp_crd_stats">
			<span class="pkp_crd_stat_pill">
				<strong>{$customDecisionsCount}</strong> {translate key="plugins.generic.customReviewerDecisionsPlugin.stats.total"}
			</span>
		</div>
	</div>

	{* General Plugin Configuration Form *}
	<div class="pkp_crd_card" style="margin-bottom: 20px;">
		<h4 class="pkp_crd_card_title">{translate key="plugins.generic.customReviewerDecisionsPlugin.generalOptions"}</h4>
		<form id="crdGeneralSettingsForm" method="post" action="{url op="manage" category="generic" plugin=$pluginName verb="saveGeneralSettings"}">
			{csrf}
			<div class="pkp_crd_options_grid">
				<label class="pkp_crd_checkbox_label">
					<input type="checkbox" name="showGuidelines" value="1" {if $showGuidelines}checked="checked"{/if} />
					<div>
						<strong>{translate key="plugins.generic.customReviewerDecisionsPlugin.showGuidelines"}</strong>
						<span class="pkp_crd_subtext">{translate key="plugins.generic.customReviewerDecisionsPlugin.showGuidelines.desc"}</span>
					</div>
				</label>

				<label class="pkp_crd_checkbox_label">
					<input type="checkbox" name="showBadges" value="1" {if $showBadges}checked="checked"{/if} />
					<div>
						<strong>{translate key="plugins.generic.customReviewerDecisionsPlugin.showBadges"}</strong>
						<span class="pkp_crd_subtext">{translate key="plugins.generic.customReviewerDecisionsPlugin.showBadges.desc"}</span>
					</div>
				</label>
			</div>
			<div style="margin-top: 12px; text-align: right;">
				<button type="button" id="crdSaveGeneralBtn" class="pkp_button pkp_button_primary">
					{translate key="common.save"}
				</button>
			</div>
		</form>
	</div>

	{* Decisions Management Section *}
	<div class="pkp_crd_card">
		<div class="pkp_crd_card_header">
			<div>
				<h4 class="pkp_crd_card_title" style="margin: 0;">{translate key="plugins.generic.customReviewerDecisionsPlugin.decisionsList"}</h4>
				<p class="description" style="margin: 4px 0 0 0; font-size: 0.85rem;">
					{translate key="plugins.generic.customReviewerDecisionsPlugin.decisionsList.desc"}
				</p>
			</div>
			<div class="pkp_crd_actions_bar">
				<button type="button" id="crdAddDecisionBtn" class="pkp_button pkp_button_primary">
					<span class="fa fa-plus"></span> {translate key="plugins.generic.customReviewerDecisionsPlugin.addDecision"}
				</button>
				<button type="button" id="crdResetDefaultsBtn" class="pkp_button pkp_button_secondary">
					<span class="fa fa-undo"></span> {translate key="plugins.generic.customReviewerDecisionsPlugin.resetDefaults"}
				</button>
			</div>
		</div>

		{* Decisions Table *}
		<div class="pkp_crd_table_container">
			<table class="pkp_crd_table" id="crdDecisionsTable">
				<thead>
					<tr>
						<th style="width: 50px; text-align: center;">#</th>
						<th style="width: 70px; text-align: center;">{translate key="plugins.generic.customReviewerDecisionsPlugin.color"}</th>
						<th>{translate key="plugins.generic.customReviewerDecisionsPlugin.decisionTitle"}</th>
						<th>{translate key="plugins.generic.customReviewerDecisionsPlugin.description"}</th>
						<th style="width: 90px; text-align: center;">{translate key="plugins.generic.customReviewerDecisionsPlugin.type"}</th>
						<th style="width: 80px; text-align: center;">{translate key="common.status"}</th>
						<th style="width: 140px; text-align: center;">{translate key="common.action"}</th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$decisions item=decision name=decisionsLoop}
						<tr data-id="{$decision.id}" class="crd_decision_row {if !$decision.enabled}crd_disabled_row{/if}">
							<td style="text-align: center; color: #64748b; font-weight: 600;">
								{$decision.sequence|default:$smarty.foreach.decisionsLoop.iteration}
							</td>
							<td style="text-align: center;">
								<span class="pkp_crd_dot pkp_crd_dot_{$decision.color|escape}"></span>
							</td>
							<td>
								<div class="pkp_crd_title_cell">
									<strong class="pkp_crd_main_title">
										{if isset($decision.name.$currentLocale) && $decision.name.$currentLocale}
											{$decision.name.$currentLocale|escape}
										{elseif isset($decision.name.$primaryLocale) && $decision.name.$primaryLocale}
											{$decision.name.$primaryLocale|escape}
										{elseif isset($decision.name.en_US) && $decision.name.en_US}
											{$decision.name.en_US|escape}
										{else}
											{$decision.key|escape}
										{/if}
									</strong>
									<span class="pkp_crd_key_tag">ID: {$decision.id} &bull; {$decision.key|escape}</span>
									<div class="pkp_crd_lang_badges">
										{foreach from=$supportedLocales item=locKey}
											{if !empty($decision.name.$locKey)}
												<span class="pkp_crd_lang_badge" title="{$localeNames.$locKey|escape}">
													{$locKey|substr:0:2|upper}
												</span>
											{else}
												<span class="pkp_crd_lang_badge pkp_crd_lang_missing" title="{translate key="plugins.generic.customReviewerDecisionsPlugin.missingTranslation"}: {$localeNames.$locKey|escape}">
													{$locKey|substr:0:2|upper}
												</span>
											{/if}
										{/foreach}
									</div>
								</div>
							</td>
							<td>
								<div class="pkp_crd_desc_cell">
									{if isset($decision.description.$currentLocale) && $decision.description.$currentLocale}
										{$decision.description.$currentLocale|escape}
									{elseif isset($decision.description.$primaryLocale) && $decision.description.$primaryLocale}
										{$decision.description.$primaryLocale|escape}
									{elseif isset($decision.description.en_US) && $decision.description.en_US}
										{$decision.description.en_US|escape}
									{else}
										<em style="color: #94a3b8;">{translate key="common.none"}</em>
									{/if}
								</div>
							</td>
							<td style="text-align: center;">
								{if $decision.isDefault}
									<span class="pkp_crd_badge pkp_crd_badge_default">{translate key="plugins.generic.customReviewerDecisionsPlugin.default"}</span>
								{else}
									<span class="pkp_crd_badge pkp_crd_badge_custom">{translate key="plugins.generic.customReviewerDecisionsPlugin.custom"}</span>
								{/if}
							</td>
							<td style="text-align: center;">
								<label class="pkp_crd_toggle_switch" title="{translate key="plugins.generic.customReviewerDecisionsPlugin.toggleStatus"}">
									<input type="checkbox" class="crd_toggle_status_btn" data-id="{$decision.id}" {if $decision.enabled}checked="checked"{/if} />
									<span class="pkp_crd_slider"></span>
								</label>
							</td>
							<td style="text-align: center;">
								<div class="pkp_crd_row_actions">
									<button type="button" class="pkp_button pkp_button_action crd_edit_btn" data-id="{$decision.id}" title="{translate key="common.edit"}">
										<span class="fa fa-edit"></span>
									</button>
									{if !$decision.isDefault}
										<button type="button" class="pkp_button pkp_button_action pkp_button_danger crd_delete_btn" data-id="{$decision.id}" title="{translate key="common.delete"}">
											<span class="fa fa-trash"></span>
										</button>
									{/if}
								</div>
							</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
	</div>

	{* Informative Guidelines Box *}
	<div class="pkp_crd_info_callout">
		<div class="pkp_crd_info_icon">
			<span class="fa fa-info-circle"></span>
		</div>
		<div class="pkp_crd_info_content">
			<h5>{translate key="plugins.generic.customReviewerDecisionsPlugin.info.title"}</h5>
			<p>{translate key="plugins.generic.customReviewerDecisionsPlugin.info.body"}</p>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		if (typeof CustomReviewerDecisionsAdmin !== 'undefined') {
			CustomReviewerDecisionsAdmin.initSettings();
		}
	});
</script>
