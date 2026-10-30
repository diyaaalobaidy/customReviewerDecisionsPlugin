<?php

/**
 * @file plugins/generic/customReviewerDecisionsPlugin/CustomReviewerDecisionsPlugin.inc.php
 *
 * Copyright (c) 2026
 * Distributed under the GNU GPL v3.
 *
 * @class CustomReviewerDecisionsPlugin
 * @ingroup plugins_generic_customReviewerDecisionsPlugin
 *
 * @brief OJS plugin that empowers Journal Managers to create, customize, translate,
 *        and manage reviewer decisions/recommendations beyond OJS defaults.
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class CustomReviewerDecisionsPlugin extends GenericPlugin {
	/** @var CustomReviewerDecisionsService|null */
	protected $_service = null;

	/**
	 * Get the service instance.
	 *
	 * @return CustomReviewerDecisionsService
	 */
	public function getService() {
		if ($this->_service === null) {
			$this->import('classes.CustomReviewerDecisionsService');
			$this->_service = new CustomReviewerDecisionsService($this);
		}
		return $this->_service;
	}

	/**
	 * @copydoc Plugin::register()
	 */
	public function register($category, $path, $mainContextId = null) {
		$success = parent::register($category, $path, $mainContextId);
		if ($success && $this->getEnabled($mainContextId)) {
			// Hook into TemplateManager to inject variables and assets
			HookRegistry::register('TemplateManager::display', array($this, 'callbackTemplateDisplay'));
			HookRegistry::register('TemplateManager::fetch', array($this, 'callbackTemplateFetch'));

			// Hook into Reviewer Review Form Step 3
			HookRegistry::register('reviewerreviewstep3form::display', array($this, 'callbackStep3Display'));
			HookRegistry::register('pkpreviewerreviewstep3form::display', array($this, 'callbackStep3Display'));
		}
		return $success;
	}

	/**
	 * @copydoc Plugin::getDisplayName()
	 */
	public function getDisplayName() {
		return __('plugins.generic.customReviewerDecisionsPlugin.displayName');
	}

	/**
	 * @copydoc Plugin::getDescription()
	 */
	public function getDescription() {
		return __('plugins.generic.customReviewerDecisionsPlugin.description');
	}

	/**
	 * Provide management action links in the plugin list.
	 *
	 * @param PKPRequest $request
	 * @param array $actionArgs
	 * @return array
	 */
	public function getActions($request, $actionArgs) {
		$actions = parent::getActions($request, $actionArgs);
		if (!$this->getEnabled()) {
			return $actions;
		}

		$router = $request->getRouter();
		import('lib.pkp.classes.linkAction.request.AjaxModal');
		$settingsAction = new LinkAction(
			'settings',
			new AjaxModal(
				$router->url(
					$request,
					null,
					null,
					'manage',
					null,
					array('verb' => 'settings', 'plugin' => $this->getName(), 'category' => 'generic')
				),
				$this->getDisplayName()
			),
			__('manager.plugins.settings'),
			null
		);

		array_unshift($actions, $settingsAction);
		return $actions;
	}

	/**
	 * Manage settings actions invoked from the plugin grid or AJAX modals.
	 *
	 * @param array $args
	 * @param PKPRequest $request
	 * @return JSONMessage
	 */
	public function manage($args, $request) {
		$context = $request->getContext();
		if (!$context) {
			return new JSONMessage(false, __('common.none'));
		}
		$contextId = (int) $context->getId();
		$service = $this->getService();
		$templateMgr = TemplateManager::getManager($request);

		$verb = $request->getUserVar('verb');

		switch ($verb) {
			case 'settings':
				// Main settings dashboard
				$decisions = $service->getDecisions($contextId);
				$supportedLocales = $context->getSupportedLocales();
				$localeNames = AppLocale::getLocaleNames();
				$primaryLocale = $context->getPrimaryLocale();

				$templateMgr->assign(array(
					'pluginName' => $this->getName(),
					'decisions' => $decisions,
					'supportedLocales' => $supportedLocales,
					'localeNames' => $localeNames,
					'primaryLocale' => $primaryLocale,
					'currentLocale' => AppLocale::getLocale(),
					'showGuidelines' => (bool) $this->getSetting($contextId, 'showGuidelines', true),
					'showBadges' => (bool) $this->getSetting($contextId, 'showBadges', true),
					'customDecisionsCount' => count($decisions),
				));

				return new JSONMessage(true, $templateMgr->fetch($this->getTemplateResource('settings/settings.tpl')));

			case 'editDecision':
				// Add / Edit decision modal
				$id = (int) $request->getUserVar('id');
				$decision = $id > 0 ? $service->getDecisionById($contextId, $id) : null;
				$supportedLocales = $context->getSupportedLocales();
				$localeNames = AppLocale::getLocaleNames();
				$primaryLocale = $context->getPrimaryLocale();

				if (!$decision) {
					// New decision scaffold
					$nextId = $service->getNextAvailableId($contextId);
					$decision = array(
						'id' => $nextId,
						'key' => 'custom_' . $nextId,
						'name' => array(),
						'description' => array(),
						'color' => 'blue',
						'badgeClass' => 'pkp_badge_info',
						'sequence' => count($service->getDecisions($contextId)) + 1,
						'enabled' => true,
						'isDefault' => false,
					);
				}

				$colorOptions = array(
					'green' => __('plugins.generic.customReviewerDecisionsPlugin.color.green'),
					'blue' => __('plugins.generic.customReviewerDecisionsPlugin.color.blue'),
					'orange' => __('plugins.generic.customReviewerDecisionsPlugin.color.orange'),
					'red' => __('plugins.generic.customReviewerDecisionsPlugin.color.red'),
					'purple' => __('plugins.generic.customReviewerDecisionsPlugin.color.purple'),
					'teal' => __('plugins.generic.customReviewerDecisionsPlugin.color.teal'),
					'darkred' => __('plugins.generic.customReviewerDecisionsPlugin.color.darkred'),
					'gray' => __('plugins.generic.customReviewerDecisionsPlugin.color.gray'),
				);

				$templateMgr->assign(array(
					'pluginName' => $this->getName(),
					'decision' => $decision,
					'supportedLocales' => $supportedLocales,
					'localeNames' => $localeNames,
					'primaryLocale' => $primaryLocale,
					'currentLocale' => AppLocale::getLocale(),
					'colorOptions' => $colorOptions,
					'isNew' => !($id > 0),
				));

				return new JSONMessage(true, $templateMgr->fetch($this->getTemplateResource('settings/decisionEditModal.tpl')));

			case 'saveDecision':
				// Save added / edited decision
				$id = (int) $request->getUserVar('id');
				$key = (string) $request->getUserVar('key');
				$color = (string) $request->getUserVar('color');
				$sequence = (int) $request->getUserVar('sequence');
				$enabled = (bool) $request->getUserVar('enabled');

				$names = (array) $request->getUserVar('name');
				$descriptions = (array) $request->getUserVar('description');

				// Ensure primary locale has at least a fallback
				$primaryLocale = $context->getPrimaryLocale();
				if (empty($names[$primaryLocale]) && !empty($names['en_US'])) {
					$names[$primaryLocale] = $names['en_US'];
				}

				$service->saveDecision($contextId, array(
					'id' => $id,
					'key' => $key,
					'name' => $names,
					'description' => $descriptions,
					'color' => $color,
					'sequence' => $sequence,
					'enabled' => $enabled,
				));

				return new JSONMessage(true, array(
					'status' => 'success',
					'message' => __('plugins.generic.customReviewerDecisionsPlugin.savedSuccessfully'),
				));

			case 'deleteDecision':
				// Delete custom decision
				$id = (int) $request->getUserVar('id');
				if ($id > 0) {
					$service->deleteDecision($contextId, $id);
				}
				return new JSONMessage(true, array(
					'status' => 'success',
					'message' => __('plugins.generic.customReviewerDecisionsPlugin.deletedSuccessfully'),
				));

			case 'toggleDecision':
				// Quick toggle enabled/disabled
				$id = (int) $request->getUserVar('id');
				$newStatus = false;
				if ($id > 0) {
					$newStatus = $service->toggleDecision($contextId, $id);
				}
				return new JSONMessage(true, array(
					'status' => 'success',
					'enabled' => $newStatus,
				));

			case 'reorderDecisions':
				// Reorder decisions
				$orderedIds = (array) $request->getUserVar('orderedIds');
				if (!empty($orderedIds)) {
					$service->reorderDecisions($contextId, $orderedIds);
				}
				return new JSONMessage(true, array('status' => 'success'));

			case 'resetDefaults':
				// Reset all decisions back to curated defaults
				$service->resetToDefaults($contextId);
				return new JSONMessage(true, array(
					'status' => 'success',
					'message' => __('plugins.generic.customReviewerDecisionsPlugin.resetSuccessfully'),
				));

			case 'saveGeneralSettings':
				// Save general options
				$showGuidelines = (bool) $request->getUserVar('showGuidelines');
				$showBadges = (bool) $request->getUserVar('showBadges');

				$this->updateSetting($contextId, 'showGuidelines', $showGuidelines, 'bool');
				$this->updateSetting($contextId, 'showBadges', $showBadges, 'bool');

				return new JSONMessage(true, array(
					'status' => 'success',
					'message' => __('plugins.generic.customReviewerDecisionsPlugin.settingsSaved'),
				));

			default:
				return parent::manage($args, $request);
		}
	}

	/**
	 * Hook callback for Step 3 form display.
	 *
	 * @param string $hookName
	 * @param array $args [Form, returner]
	 * @return bool
	 */
	public function callbackStep3Display($hookName, $args) {
		$form = $args[0];
		$request = Application::get()->getRequest();
		$context = $request->getContext();
		if (!$context) return false;

		$templateMgr = TemplateManager::getManager($request);
		$service = $this->getService();
		$locale = AppLocale::getLocale();

		// Override reviewerRecommendationOptions with custom localized decisions
		$options = $service->getLocalizedOptions($context->getId(), $locale, false);
		$templateMgr->assign('reviewerRecommendationOptions', $options);

		return false;
	}

	/**
	 * Hook callback for TemplateManager::display.
	 * Injects CSS and JS assets on workflow and review pages.
	 *
	 * @param string $hookName
	 * @param array $args [TemplateManager, template, output]
	 * @return bool
	 */
	public function callbackTemplateDisplay($hookName, $args) {
		$templateMgr = $args[0];
		$template = $args[1];
		$request = Application::get()->getRequest();
		$context = $request->getContext();
		if (!$context) return false;

		$contextId = (int) $context->getId();
		$baseUrl = $request->getBaseUrl() . '/' . $this->getPluginPath();

		// Add custom stylesheet
		$templateMgr->addStylesheet(
			'customReviewerDecisionsCss',
			$baseUrl . '/css/customReviewerDecisions.css',
			array('contexts' => array('backend', 'frontend'))
		);

		// Add custom script
		$templateMgr->addJavaScript(
			'customReviewerDecisionsJs',
			$baseUrl . '/js/customReviewerDecisions.js',
			array('contexts' => array('backend', 'frontend'))
		);

		// Provide decisions metadata to JavaScript
		$service = $this->getService();
		$locale = AppLocale::getLocale();
		$metadata = $service->getMetadataMap($contextId, $locale);
		$showGuidelines = (bool) $this->getSetting($contextId, 'showGuidelines', true);
		$showBadges = (bool) $this->getSetting($contextId, 'showBadges', true);

		$clientData = array(
			'decisions' => $metadata,
			'showGuidelines' => $showGuidelines,
			'showBadges' => $showBadges,
			'guidelinesLabel' => __('plugins.generic.customReviewerDecisionsPlugin.guidelinesLabel'),
			'selectDecisionHint' => __('plugins.generic.customReviewerDecisionsPlugin.selectDecisionHint'),
			'manageUrl' => $request->getRouter()->url(
				$request,
				null,
				null,
				'manage',
				null,
				array('plugin' => $this->getName(), 'category' => 'generic')
			),
		);

		$scriptTag = '<script type="text/javascript">var customReviewerDecisionsConfig = ' . json_encode($clientData) . ';</script>';
		$templateMgr->addHeader('customReviewerDecisionsConfig', $scriptTag);

		return false;
	}

	/**
	 * Hook callback for TemplateManager::fetch.
	 * Intercepts reviewer forms, editor modals, and grid templates to replace options and decorate badges.
	 *
	 * @param string $hookName
	 * @param array $args [TemplateManager, template, cache_id, compile_id, result]
	 * @return bool
	 */
	public function callbackTemplateFetch($hookName, $args) {
		$templateMgr = $args[0];
		$template = $args[1];
		$request = Application::get()->getRequest();
		$context = $request ? $request->getContext() : null;
		if (!$context) return false;

		$contextId = (int) $context->getId();
		$service = $this->getService();
		$locale = AppLocale::getLocale();

		// Target review recommendation templates
		if (
			strpos($template, 'reviewerRecommendations.tpl') !== false ||
			strpos($template, 'step3.tpl') !== false ||
			strpos($template, 'readReview.tpl') !== false ||
			strpos($template, 'authorReadReview.tpl') !== false
		) {
			$options = $service->getLocalizedOptions($contextId, $locale, false);
			$templateMgr->assign('reviewerRecommendationOptions', $options);
		}

		return false;
	}

	/**
	 * Get the template path for this plugin.
	 *
	 * @param string $template
	 * @return string
	 */
	public function getTemplateResource($template) {
		return $this->getPluginPath() . '/templates/' . $template;
	}
}
