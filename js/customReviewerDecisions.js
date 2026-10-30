/**
 * plugins/generic/customReviewerDecisionsPlugin/js/customReviewerDecisions.js
 *
 * Copyright (c) 2026
 * Distributed under the GNU GPL v3.
 *
 * Client-side logic for Custom Reviewer Decisions plugin:
 * - Admin settings management (modals, toggles, AJAX operations)
 * - Reviewer Step 3 interactive guidelines & live preview
 * - Workflow grid badge rendering
 */

(function($) {
	'use strict';

	var CustomReviewerDecisionsAdmin = {
		/**
		 * Initialize admin settings dashboard
		 */
		initSettings: function() {
			var self = this;
			var $wrapper = $('.pkp_crd_wrapper');
			if (!$wrapper.length) return;

			// Add Decision Button
			$('#crdAddDecisionBtn').off('click').on('click', function(e) {
				e.preventDefault();
				self.openDecisionModal(0);
			});

			// Edit Decision Buttons
			$('.crd_edit_btn').off('click').on('click', function(e) {
				e.preventDefault();
				var id = $(this).data('id');
				self.openDecisionModal(id);
			});

			// Delete Decision Buttons
			$('.crd_delete_btn').off('click').on('click', function(e) {
				e.preventDefault();
				var id = $(this).data('id');
				if (confirm('Are you sure you want to delete this custom decision? Submissions using this decision may no longer display it properly.')) {
					self.deleteDecision(id);
				}
			});

			// Toggle Status Buttons
			$('.crd_toggle_status_btn').off('change').on('change', function(e) {
				var id = $(this).data('id');
				var $row = $(this).closest('tr');
				self.toggleStatus(id, $(this), $row);
			});

			// Reset to Defaults Button
			$('#crdResetDefaultsBtn').off('click').on('click', function(e) {
				e.preventDefault();
				if (confirm('Are you sure you want to reset all reviewer decisions to system defaults? Custom decisions will be preserved or replaced by curated defaults.')) {
					self.resetDefaults();
				}
			});

			// Save General Settings
			$('#crdSaveGeneralBtn').off('click').on('click', function(e) {
				e.preventDefault();
				self.saveGeneralSettings();
			});
		},

		/**
		 * Open Add / Edit Modal
		 */
		openDecisionModal: function(id) {
			var url = this.getManageUrl('editDecision') + '&id=' + id;
			var title = id > 0 ? 'Edit Reviewer Decision' : 'Add Custom Reviewer Decision';

			var $modal = $('<div id="crdDecisionModalContainer"></div>');
			$('body').append($modal);

			$modal.pkpHandler('$.pkp.controllers.modal.AjaxModalHandler', {
				title: title,
				url: url,
				closeOnEscape: true
			});
		},

		/**
		 * Initialize Edit Modal
		 */
		initModal: function() {
			var self = this;
			var $form = $('#crdDecisionEditForm');
			if (!$form.length) return;

			// Tab switching
			$('.pkp_crd_tab_btn').off('click').on('click', function(e) {
				e.preventDefault();
				var targetId = $(this).data('target');
				$('.pkp_crd_tab_btn').removeClass('active');
				$(this).addClass('active');

				$('.pkp_crd_tab_pane').removeClass('active');
				$('#' + targetId).addClass('active');
			});

			// Color option selection
			$('.pkp_crd_color_option').off('click').on('click', function() {
				$('.pkp_crd_color_option').removeClass('selected');
				$(this).addClass('selected');
			});

			// Close modal button
			$('.pkp_modal_close_btn').off('click').on('click', function(e) {
				e.preventDefault();
				var $dialog = $(this).closest('.ui-dialog-content');
				if ($dialog.length) {
					$dialog.dialog('close');
				}
			});

			// Form submission via AJAX
			$form.off('submit').on('submit', function(e) {
				e.preventDefault();
				var $btn = $('#crdSaveDecisionBtn');
				$btn.prop('disabled', true).text('Saving...');

				$.ajax({
					url: $form.attr('action'),
					type: 'POST',
					data: $form.serialize(),
					dataType: 'json',
					success: function(response) {
						if (response && response.status === true) {
							var $dialog = $form.closest('.ui-dialog-content');
							if ($dialog.length) {
								$dialog.dialog('close');
							}
							self.reloadSettingsDashboard();
						} else {
							alert(response.content || 'Error saving decision.');
							$btn.prop('disabled', false).text('Save');
						}
					},
					error: function() {
						alert('A network error occurred while saving the decision.');
						$btn.prop('disabled', false).text('Save');
					}
				});
			});
		},

		/**
		 * Quick toggle decision active/inactive
		 */
		toggleStatus: function(id, $checkbox, $row) {
			var url = this.getManageUrl('toggleDecision') + '&id=' + id;
			$.ajax({
				url: url,
				type: 'POST',
				dataType: 'json',
				success: function(response) {
					if (response && response.status === true) {
						if (response.content && response.content.enabled) {
							$row.removeClass('crd_disabled_row');
						} else {
							$row.addClass('crd_disabled_row');
						}
					} else {
						$checkbox.prop('checked', !$checkbox.prop('checked'));
					}
				},
				error: function() {
					$checkbox.prop('checked', !$checkbox.prop('checked'));
					alert('Failed to update decision status.');
				}
			});
		},

		/**
		 * Delete decision
		 */
		deleteDecision: function(id) {
			var self = this;
			var url = this.getManageUrl('deleteDecision') + '&id=' + id;
			$.ajax({
				url: url,
				type: 'POST',
				dataType: 'json',
				success: function(response) {
					if (response && response.status === true) {
						self.reloadSettingsDashboard();
					} else {
						alert('Error deleting decision.');
					}
				},
				error: function() {
					alert('Network error while deleting decision.');
				}
			});
		},

		/**
		 * Reset to curated defaults
		 */
		resetDefaults: function() {
			var self = this;
			var url = this.getManageUrl('resetDefaults');
			$.ajax({
				url: url,
				type: 'POST',
				dataType: 'json',
				success: function(response) {
					if (response && response.status === true) {
						self.reloadSettingsDashboard();
					} else {
						alert('Error resetting defaults.');
					}
				},
				error: function() {
					alert('Network error while resetting defaults.');
				}
			});
		},

		/**
		 * Save general plugin settings
		 */
		saveGeneralSettings: function() {
			var $form = $('#crdGeneralSettingsForm');
			var $btn = $('#crdSaveGeneralBtn');
			var originalText = $btn.text();
			$btn.prop('disabled', true).text('Saving...');

			$.ajax({
				url: $form.attr('action'),
				type: 'POST',
				data: $form.serialize(),
				dataType: 'json',
				success: function(response) {
					$btn.prop('disabled', false).text(originalText);
					if (response && response.status === true) {
						$btn.css('background-color', '#10b981').text('Saved!');
						setTimeout(function() {
							$btn.css('background-color', '').text(originalText);
						}, 2000);
					} else {
						alert('Failed to save settings.');
					}
				},
				error: function() {
					$btn.prop('disabled', false).text(originalText);
					alert('Network error while saving settings.');
				}
			});
		},

		/**
		 * Reload the settings dashboard in place
		 */
		reloadSettingsDashboard: function() {
			var $wrapper = $('.pkp_crd_wrapper');
			var $dialog = $wrapper.closest('.ui-dialog-content');
			if ($dialog.length) {
				var url = this.getManageUrl('settings');
				$dialog.load(url);
			} else {
				location.reload();
			}
		},

		/**
		 * Generate manage URL for a given verb
		 */
		getManageUrl: function(verb) {
			if (typeof customReviewerDecisionsConfig !== 'undefined' && customReviewerDecisionsConfig.manageUrl) {
				return customReviewerDecisionsConfig.manageUrl + '&verb=' + verb;
			}
			return '?verb=' + verb;
		}
	};

	var CustomReviewerDecisionsFrontend = {
		/**
		 * Initialize reviewer step 3 recommendations enhancement
		 */
		initReviewerStep: function() {
			var $select = $('select#recommendation');
			if (!$select.length) return;

			var config = window.customReviewerDecisionsConfig || {};
			var decisions = config.decisions || {};
			var showGuidelines = config.showGuidelines !== false;

			// Create guidelines box if not present
			var $guidelinesBox = $('#crdReviewerGuidelinesBox');
			if (!$guidelinesBox.length && showGuidelines) {
				$guidelinesBox = $(
					'<div id="crdReviewerGuidelinesBox" class="pkp_crd_guidelines_box" style="display: none; margin-top: 12px;">' +
						'<div class="pkp_crd_guidelines_header">' +
							'<span class="fa fa-lightbulb-o pkp_crd_guidelines_icon"></span> ' +
							'<strong id="crdGuidelinesTitle">' + (config.guidelinesLabel || 'Reviewer Guidelines') + '</strong> ' +
							'<span id="crdGuidelinesBadge" class="pkp_crd_badge" style="display: none;"></span>' +
						'</div>' +
						'<div id="crdGuidelinesContent" class="pkp_crd_guidelines_content" style="margin-top: 6px; font-size: 0.9rem; color: #475569;"></div>' +
					'</div>'
				);
				$select.closest('.pkp_form_element, .fbvFormSection, div').append($guidelinesBox);
			}

			function updateGuideline(selectedId) {
				if (!showGuidelines || !$guidelinesBox.length) return;

				if (!selectedId || !decisions[selectedId]) {
					$guidelinesBox.slideUp(180);
					return;
				}

				var decision = decisions[selectedId];
				var desc = decision.description;
				var badgeClass = decision.badgeClass || 'pkp_badge_info';
				var color = decision.color || 'blue';

				if (desc && desc.trim().length > 0) {
					$('#crdGuidelinesContent').html(desc);
					$('#crdGuidelinesBadge')
						.attr('class', 'pkp_crd_badge pkp_crd_badge_' + color)
						.text(decision.title)
						.show();
					$guidelinesBox.slideDown(180);
				} else {
					$guidelinesBox.slideUp(180);
				}
			}

			$select.off('change.crd').on('change.crd', function() {
				updateGuideline($(this).val());
			});

			// Trigger on initial load if an option is selected
			var currentVal = $select.val();
			if (currentVal) {
				updateGuideline(currentVal);
			}
		},

		/**
		 * Enhance workflow and reviewer grid recommendations with colored badges
		 */
		initWorkflowBadges: function() {
			var config = window.customReviewerDecisionsConfig || {};
			if (config.showBadges === false) return;

			var decisions = config.decisions || {};

			// Function to scan and decorate recommendation spans in grids and modals
			function decorateRecommendations() {
				$('.pkp_controllers_informationCenter_itemLastEvent, .gridCell .details, .pkp_grid_description').each(function() {
					var $el = $(this);
					if ($el.data('crd_processed')) return;

					var text = $el.text();
					if (text.indexOf('Recommendation:') !== -1 || text.indexOf('Preporuka:') !== -1 || text.indexOf('التوصية:') !== -1 || text.indexOf('Recommandation') !== -1) {
						for (var id in decisions) {
							if (decisions.hasOwnProperty(id)) {
								var title = decisions[id].title;
								if (title && text.indexOf(title) !== -1) {
									var color = decisions[id].color || 'blue';
									var badgeHtml = '<span class="pkp_crd_badge pkp_crd_badge_' + color + '">' + title + '</span>';
									var newHtml = $el.html().replace(title, badgeHtml);
									$el.html(newHtml);
									$el.data('crd_processed', true);
									break;
								}
							}
						}
					}
				});
			}

			decorateRecommendations();

			// Also listen for AJAX grid updates
			$(document).ajaxComplete(function() {
				setTimeout(decorateRecommendations, 100);
			});
		}
	};

	// Expose to window
	window.CustomReviewerDecisionsAdmin = CustomReviewerDecisionsAdmin;
	window.CustomReviewerDecisionsFrontend = CustomReviewerDecisionsFrontend;

	// Auto-run on DOM ready
	$(document).ready(function() {
		CustomReviewerDecisionsFrontend.initReviewerStep();
		CustomReviewerDecisionsFrontend.initWorkflowBadges();
	});

})(jQuery);
