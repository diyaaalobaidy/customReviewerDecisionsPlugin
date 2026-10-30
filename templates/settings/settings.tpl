{**
 * plugins/generic/customReviewerDecisionsPlugin/templates/settings/settings.tpl
 *
 * Copyright (c) 2026
 * Distributed under the GNU GPL v3.
 *
 * Custom Reviewer Decisions Plugin - Journal Manager Management Dashboard (OJS 3.5).
 *}

<div class="pkp_crd_wrapper" id="crdSettingsRoot">
	{* Header Banner *}
	<div class="pkp_crd_header">
		<div class="pkp_crd_title_area">
			<h3>{translate key="plugins.generic.customReviewerDecisionsPlugin.settings.title"}</h3>
			<p class="description">{translate key="plugins.generic.customReviewerDecisionsPlugin.settings.description"}</p>
		</div>
		<div class="pkp_crd_stats">
			<span class="pkp_crd_stat_pill">
				<strong id="crdDecisionsCountBadge">{$customDecisionsCount}</strong> {translate key="plugins.generic.customReviewerDecisionsPlugin.stats.total"}
			</span>
		</div>
	</div>

	{* VIEW 1: DECISIONS LIST & PREFERENCES *}
	<div id="crdListView">
		{* General Plugin Configuration Form *}
		<div class="pkp_crd_card" style="margin-bottom: 20px;">
			<h4 class="pkp_crd_card_title">{translate key="plugins.generic.customReviewerDecisionsPlugin.generalOptions"}</h4>
			<form id="crdGeneralSettingsForm" method="post" action="{$saveGeneralSettingsUrl}">
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
				<div style="margin-top: 14px; text-align: right;">
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
							<th style="width: 130px; text-align: center;">{translate key="common.action"}</th>
						</tr>
					</thead>
					<tbody id="crdDecisionsTableBody">
						{foreach from=$decisions item=decision name=decisionsLoop}
							<tr data-id="{$decision.id}" class="crd_decision_row {if !$decision.enabled}crd_disabled_row{/if}">
								<td style="text-align: center; color: #64748b; font-weight: 600;" class="crd_col_seq">
									{$decision.sequence|default:$smarty.foreach.decisionsLoop.iteration}
								</td>
								<td style="text-align: center;" class="crd_col_dot">
									<span class="pkp_crd_dot pkp_crd_dot_{$decision.color|escape}"></span>
								</td>
								<td class="crd_col_title">
									<div class="pkp_crd_title_cell">
										<strong class="pkp_crd_main_title">
											{if isset($decision.name.$currentLocale) && $decision.name.$currentLocale}
												{$decision.name.$currentLocale|escape}
											{elseif isset($decision.name.$primaryLocale) && $decision.name.$primaryLocale}
												{$decision.name.$primaryLocale|escape}
											{elseif isset($decision.name.en) && $decision.name.en}
												{$decision.name.en|escape}
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
													<span class="pkp_crd_lang_badge" title="{$localeNames.$locKey|default:$locKey|escape}">
														{$locKey|substr:0:2|upper}
													</span>
												{else}
													<span class="pkp_crd_lang_badge pkp_crd_lang_missing" title="{translate key="plugins.generic.customReviewerDecisionsPlugin.missingTranslation"}: {$localeNames.$locKey|default:$locKey|escape}">
														{$locKey|substr:0:2|upper}
													</span>
												{/if}
											{/foreach}
										</div>
									</div>
								</td>
								<td class="crd_col_desc">
									<div class="pkp_crd_desc_cell">
										{if isset($decision.description.$currentLocale) && $decision.description.$currentLocale}
											{$decision.description.$currentLocale|escape}
										{elseif isset($decision.description.$primaryLocale) && $decision.description.$primaryLocale}
											{$decision.description.$primaryLocale|escape}
										{elseif isset($decision.description.en) && $decision.description.en}
											{$decision.description.en|escape}
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
											<span class="fa fa-edit"></span> {translate key="common.edit"}
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

		{* Informative Callout *}
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

	{* VIEW 2: EMBEDDED ADD / EDIT DECISION FORM *}
	<div id="crdEditView" style="display: none;">
		<div class="pkp_crd_card">
			<div class="pkp_crd_card_header">
				<div>
					<button type="button" id="crdCancelTopBtn" class="pkp_button pkp_button_secondary" style="margin-bottom: 8px;">
						<span class="fa fa-arrow-left"></span> {translate key="common.back"}
					</button>
					<h4 class="pkp_crd_card_title" id="crdFormHeading" style="margin: 0;"></h4>
					<p class="description" style="margin: 4px 0 0 0; font-size: 0.85rem;">
						{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.subtitle"}
					</p>
				</div>
			</div>

			<form id="crdDecisionEditForm" method="post" action="{$saveDecisionUrl}">
				{csrf}
				<input type="hidden" id="crdEditId" name="id" value="" />

				{* Top Row: Core Metadata *}
				<div class="pkp_crd_form_row pkp_crd_two_col">
					<div class="pkp_crd_field_group">
						<label for="crdKey">
							<strong>{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.key"}</strong>
							<span class="pkp_crd_help">{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.key.desc"}</span>
						</label>
						<input type="text" id="crdKey" name="key" value="" class="pkp_input" required="required" placeholder="e.g. majorRevisions" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 4px;" />
					</div>

					<div class="pkp_crd_field_group">
						<label for="crdSequence">
							<strong>{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.sequence"}</strong>
							<span class="pkp_crd_help">{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.sequence.desc"}</span>
						</label>
						<input type="number" id="crdSequence" name="sequence" value="1" min="1" max="99" class="pkp_input" style="width: 100%; max-width: 140px; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 4px;" />
					</div>
				</div>

				{* Color / Badge Selector *}
				<div class="pkp_crd_form_row" style="margin-top: 14px;">
					<label>
						<strong>{translate key="plugins.generic.customReviewerDecisionsPlugin.color"}</strong>
						<span class="pkp_crd_help">{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.color.desc"}</span>
					</label>
					<div class="pkp_crd_color_picker" id="crdColorPicker">
						{foreach from=$colorOptions key=colorKey item=colorLabel}
							<label class="pkp_crd_color_option" data-color="{$colorKey}">
								<input type="radio" name="color" value="{$colorKey|escape}" />
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

					{* Locale Tabs Nav *}
					<div class="pkp_crd_tabs_nav" id="crdLocaleTabsNav">
						{foreach from=$supportedLocales item=locKey name=locLoop}
							<button type="button" class="pkp_crd_tab_btn {if $locKey == $currentLocale || ($smarty.foreach.locLoop.first && !in_array($currentLocale, $supportedLocales))}active{/if}" data-target="crd_tab_{$locKey}">
								<span class="pkp_crd_tab_lang_code">{$locKey|substr:0:2|upper}</span>
								{$localeNames.$locKey|default:$locKey|escape}
								{if $locKey == $primaryLocale}
									<span class="pkp_crd_primary_badge">({translate key="admin.settings.primaryLocale"})</span>
								{/if}
							</button>
						{/foreach}
					</div>

					{* Locale Tab Panes *}
					<div class="pkp_crd_tabs_content">
						{foreach from=$supportedLocales item=locKey name=locLoop2}
							<div class="pkp_crd_tab_pane {if $locKey == $currentLocale || ($smarty.foreach.locLoop2.first && !in_array($currentLocale, $supportedLocales))}active{/if}" id="crd_tab_{$locKey}">
								<div class="pkp_crd_field_group" style="margin-bottom: 14px;">
									<label for="crdName_{$locKey}">
										<strong>{translate key="plugins.generic.customReviewerDecisionsPlugin.decisionTitle"} ({$localeNames.$locKey|default:$locKey|escape})</strong>
										{if $locKey == $primaryLocale}<span class="pkp_crd_required">*</span>{/if}
									</label>
									<input type="text" id="crdName_{$locKey}" name="name[{$locKey}]" value="" class="pkp_input" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 4px;" placeholder="{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.titlePlaceholder"}" />
								</div>

								<div class="pkp_crd_field_group">
									<label for="crdDesc_{$locKey}">
										<strong>{translate key="plugins.generic.customReviewerDecisionsPlugin.description"} / {translate key="plugins.generic.customReviewerDecisionsPlugin.reviewerGuidelines"} ({$localeNames.$locKey|default:$locKey|escape})</strong>
									</label>
									<textarea id="crdDesc_{$locKey}" name="description[{$locKey}]" rows="3" class="pkp_input" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 4px; resize: vertical;" placeholder="{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.descPlaceholder"}"></textarea>
								</div>
							</div>
						{/foreach}
					</div>
				</div>

				{* Status Checkbox *}
				<div class="pkp_crd_form_row" style="margin-top: 18px;">
					<label class="pkp_crd_checkbox_label">
						<input type="hidden" name="enabled" value="0" />
						<input type="checkbox" id="crdEditEnabled" name="enabled" value="1" checked="checked" />
						<div>
							<strong>{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.enableDecision"}</strong>
							<span class="pkp_crd_subtext">{translate key="plugins.generic.customReviewerDecisionsPlugin.modal.enableDecision.desc"}</span>
						</div>
					</label>
				</div>

				{* Footer Buttons *}
				<div class="pkp_crd_modal_footer">
					<button type="button" id="crdCancelBottomBtn" class="pkp_button pkp_button_secondary">
						{translate key="common.cancel"}
					</button>
					<button type="submit" id="crdSaveDecisionSubmitBtn" class="pkp_button pkp_button_primary">
						<span class="fa fa-check"></span> {translate key="common.save"}
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

{* INLINE SCRIPT: EXECUTES IMMEDIATELY UPON INJECTION INTO DOM *}
<script type="text/javascript">
(function($) {
	// Raw decisions dictionary from server
	var decisionsData = {$decisions|@json_encode};
	var supportedLocales = {$supportedLocales|@json_encode};
	var currentLocale = "{$currentLocale|escape:'javascript'}";
	var primaryLocale = "{$primaryLocale|escape:'javascript'}";
	var nextAvailableId = {$nextDecisionId|default:10};

	var urls = {
		saveDecision: "{$saveDecisionUrl|escape:'javascript'}",
		deleteDecision: "{$deleteDecisionUrl|escape:'javascript'}",
		toggleDecision: "{$toggleDecisionUrl|escape:'javascript'}",
		resetDefaults: "{$resetDefaultsUrl|escape:'javascript'}",
		saveGeneralSettings: "{$saveGeneralSettingsUrl|escape:'javascript'}",
		settings: "{$settingsUrl|escape:'javascript'}"
	};

	function notify(message, type) {
		type = type || 'success';
		if (window.pkp && pkp.eventBus) {
			pkp.eventBus.$emit('notify', message, type);
		} else {
			alert(message);
		}
	}

	function getDecisionById(id) {
		id = parseInt(id, 10);
		for (var i = 0; i < decisionsData.length; i++) {
			if (parseInt(decisionsData[i].id, 10) === id) {
				return decisionsData[i];
			}
		}
		return null;
	}

	function showListView() {
		$('#crdEditView').hide();
		$('#crdListView').fadeIn(150);
	}

	function showEditView(decision) {
		$('#crdListView').hide();
		var isNew = !decision;

		if (isNew) {
			$('#crdFormHeading').html('<span class="fa fa-plus-circle"></span> {translate key="plugins.generic.customReviewerDecisionsPlugin.modal.addTitle"}');
			$('#crdEditId').val(nextAvailableId);
			$('#crdKey').val('custom_' + nextAvailableId);
			$('#crdSequence').val(decisionsData.length + 1);
			$('#crdEditEnabled').prop('checked', true);

			// Clear text fields
			for (var j = 0; j < supportedLocales.length; j++) {
				var loc = supportedLocales[j];
				$('#crdName_' + loc).val('');
				$('#crdDesc_' + loc).val('');
			}
			// Select blue by default
			setColorSelection('blue');
		} else {
			$('#crdFormHeading').html('<span class="fa fa-edit"></span> {translate key="plugins.generic.customReviewerDecisionsPlugin.modal.editTitle" id=""}'.replace('()', '(ID: ' + decision.id + ')'));
			$('#crdEditId').val(decision.id);
			$('#crdKey').val(decision.key || ('custom_' + decision.id));
			$('#crdSequence').val(decision.sequence || 1);
			$('#crdEditEnabled').prop('checked', !!decision.enabled);

			// Populate locale fields
			for (var k = 0; k < supportedLocales.length; k++) {
				var lk = supportedLocales[k];
				var nameVal = (decision.name && decision.name[lk]) ? decision.name[lk] : '';
				if (!nameVal && decision.name) {
					// Check prefix fallback
					var pfx = lk.substring(0, 2);
					for (var key in decision.name) {
						if (key.substring(0, 2) === pfx && decision.name[key]) {
							nameVal = decision.name[key];
							break;
						}
					}
				}
				$('#crdName_' + lk).val(nameVal);

				var descVal = (decision.description && decision.description[lk]) ? decision.description[lk] : '';
				if (!descVal && decision.description) {
					var pfxD = lk.substring(0, 2);
					for (var keyD in decision.description) {
						if (keyD.substring(0, 2) === pfxD && decision.description[keyD]) {
							descVal = decision.description[keyD];
							break;
						}
					}
				}
				$('#crdDesc_' + lk).val(descVal);
			}

			setColorSelection(decision.color || 'blue');
		}

		// Reset active tab to current locale
		$('#crdLocaleTabsNav .pkp_crd_tab_btn').removeClass('active');
		$('.pkp_crd_tab_pane').removeClass('active');
		var $activeBtn = $('#crdLocaleTabsNav .pkp_crd_tab_btn[data-target="crd_tab_' + currentLocale + '"]');
		if (!$activeBtn.length) {
			$activeBtn = $('#crdLocaleTabsNav .pkp_crd_tab_btn:first');
		}
		$activeBtn.addClass('active');
		$('#' + $activeBtn.data('target')).addClass('active');

		$('#crdEditView').fadeIn(150);
	}

	function setColorSelection(color) {
		$('#crdColorPicker .pkp_crd_color_option').removeClass('selected');
		var $target = $('#crdColorPicker .pkp_crd_color_option[data-color="' + color + '"]');
		if ($target.length) {
			$target.addClass('selected');
			$target.find('input[type="radio"]').prop('checked', true);
		} else {
			var $first = $('#crdColorPicker .pkp_crd_color_option:first');
			$first.addClass('selected');
			$first.find('input[type="radio"]').prop('checked', true);
		}
	}

	// Tab switcher
	$(document).off('click', '#crdLocaleTabsNav .pkp_crd_tab_btn').on('click', '#crdLocaleTabsNav .pkp_crd_tab_btn', function(e) {
		e.preventDefault();
		var targetId = $(this).data('target');
		$('#crdLocaleTabsNav .pkp_crd_tab_btn').removeClass('active');
		$(this).addClass('active');
		$('.pkp_crd_tab_pane').removeClass('active');
		$('#' + targetId).addClass('active');
	});

	// Color option clicks
	$(document).off('click', '#crdColorPicker .pkp_crd_color_option').on('click', '#crdColorPicker .pkp_crd_color_option', function() {
		var col = $(this).data('color');
		setColorSelection(col);
	});

	// Add Decision Button
	$(document).off('click', '#crdAddDecisionBtn').on('click', '#crdAddDecisionBtn', function(e) {
		e.preventDefault();
		showEditView(null);
	});

	// Edit Button in table
	$(document).off('click', '.crd_edit_btn').on('click', '.crd_edit_btn', function(e) {
		e.preventDefault();
		var id = $(this).data('id');
		var decision = getDecisionById(id);
		if (decision) {
			showEditView(decision);
		}
	});

	// Cancel Buttons
	$(document).off('click', '#crdCancelTopBtn, #crdCancelBottomBtn').on('click', '#crdCancelTopBtn, #crdCancelBottomBtn', function(e) {
		e.preventDefault();
		showListView();
	});

	// Toggle Status checkbox
	$(document).off('change', '.crd_toggle_status_btn').on('change', '.crd_toggle_status_btn', function(e) {
		var $chk = $(this);
		var id = $chk.data('id');
		var $row = $chk.closest('tr');
		var csrfToken = $('input[name="csrfToken"]').val();

		$.ajax({
			url: urls.toggleDecision,
			type: 'POST',
			data: { id: id, csrfToken: csrfToken },
			dataType: 'json',
			success: function(resp) {
				if (resp && resp.status === true) {
					var enabled = resp.content && resp.content.enabled;
					if (enabled) {
						$row.removeClass('crd_disabled_row');
					} else {
						$row.addClass('crd_disabled_row');
					}
					var d = getDecisionById(id);
					if (d) d.enabled = enabled;
				} else {
					$chk.prop('checked', !$chk.prop('checked'));
					alert('Failed to update status.');
				}
			},
			error: function() {
				$chk.prop('checked', !$chk.prop('checked'));
				alert('Network error updating status.');
			}
		});
	});

	// Delete Button
	$(document).off('click', '.crd_delete_btn').on('click', '.crd_delete_btn', function(e) {
		e.preventDefault();
		var id = $(this).data('id');
		if (!confirm('{translate key="common.confirmDelete"}')) return;

		var csrfToken = $('input[name="csrfToken"]').val();
		var $row = $(this).closest('tr');

		$.ajax({
			url: urls.deleteDecision,
			type: 'POST',
			data: { id: id, csrfToken: csrfToken },
			dataType: 'json',
			success: function(resp) {
				if (resp && resp.status === true) {
					$row.fadeOut(200, function() {
						$row.remove();
					});
					// Remove from decisionsData
					for (var i = 0; i < decisionsData.length; i++) {
						if (parseInt(decisionsData[i].id, 10) === parseInt(id, 10)) {
							decisionsData.splice(i, 1);
							break;
						}
					}
					$('#crdDecisionsCountBadge').text(decisionsData.length);
					notify('{translate key="plugins.generic.customReviewerDecisionsPlugin.deletedSuccessfully"}', 'success');
				} else {
					alert((resp && resp.content && resp.content.message) || 'Error deleting decision.');
				}
			},
			error: function() {
				alert('Network error deleting decision.');
			}
		});
	});

	// Reset Defaults Button
	$(document).off('click', '#crdResetDefaultsBtn').on('click', '#crdResetDefaultsBtn', function(e) {
		e.preventDefault();
		if (!confirm('Are you sure you want to reset all reviewer decisions to system defaults?')) return;

		var csrfToken = $('input[name="csrfToken"]').val();
		var $btn = $(this);
		var orig = $btn.html();
		$btn.prop('disabled', true).text('Resetting...');

		$.ajax({
			url: urls.resetDefaults,
			type: 'POST',
			data: { csrfToken: csrfToken },
			dataType: 'json',
			success: function(resp) {
				$btn.prop('disabled', false).html(orig);
				if (resp && resp.status === true) {
					notify('{translate key="plugins.generic.customReviewerDecisionsPlugin.resetSuccessfully"}', 'success');
					// Reload settings dialog content
					var $modal = $('#crdSettingsRoot').closest('.ui-dialog-content');
					if ($modal.length) {
						$modal.load(urls.settings);
					} else {
						location.reload();
					}
				} else {
					alert('Failed to reset defaults.');
				}
			},
			error: function() {
				$btn.prop('disabled', false).html(orig);
				alert('Network error resetting defaults.');
			}
		});
	});

	// Save General Settings Button
	$(document).off('click', '#crdSaveGeneralBtn').on('click', '#crdSaveGeneralBtn', function(e) {
		e.preventDefault();
		var $form = $('#crdGeneralSettingsForm');
		var $btn = $(this);
		var origText = $btn.text();
		$btn.prop('disabled', true).text('Saving...');

		$.ajax({
			url: urls.saveGeneralSettings,
			type: 'POST',
			data: $form.serialize(),
			dataType: 'json',
			success: function(resp) {
				$btn.prop('disabled', false).text(origText);
				if (resp && resp.status === true) {
					notify('{translate key="plugins.generic.customReviewerDecisionsPlugin.settingsSaved"}', 'success');
				} else {
					alert('Failed to save preferences.');
				}
			},
			error: function() {
				$btn.prop('disabled', false).text(origText);
				alert('Network error saving preferences.');
			}
		});
	});

	// Save Decision Form Submit
	$(document).off('submit', '#crdDecisionEditForm').on('submit', '#crdDecisionEditForm', function(e) {
		e.preventDefault();
		var $form = $(this);
		var $btn = $('#crdSaveDecisionSubmitBtn');
		var orig = $btn.html();
		$btn.prop('disabled', true).text('Saving...');

		$.ajax({
			url: urls.saveDecision,
			type: 'POST',
			data: $form.serialize(),
			dataType: 'json',
			success: function(resp) {
				$btn.prop('disabled', false).html(orig);
				if (resp && resp.status === true) {
					notify('{translate key="plugins.generic.customReviewerDecisionsPlugin.savedSuccessfully"}', 'success');
					// Reload modal content to show fresh table
					var $modal = $('#crdSettingsRoot').closest('.ui-dialog-content');
					if ($modal.length) {
						$modal.load(urls.settings);
					} else {
						location.reload();
					}
				} else {
					alert((resp && resp.content && resp.content.message) || 'Failed to save decision.');
				}
			},
			error: function() {
				$btn.prop('disabled', false).html(orig);
				alert('Network error saving decision.');
			}
		});
	});

})(jQuery);
</script>
