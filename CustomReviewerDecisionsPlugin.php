<?php

/**
 * @file plugins/generic/customReviewerDecisionsPlugin/CustomReviewerDecisionsPlugin.php
 *
 * Copyright (c) 2026
 * Distributed under the GNU GPL v3.
 *
 * @class CustomReviewerDecisionsPlugin
 * @ingroup plugins_generic_customReviewerDecisionsPlugin
 *
 * @brief OJS 3.5 generic plugin that empowers Journal Managers to create, customize, translate,
 *        and manage reviewer decisions/recommendations beyond OJS defaults.
 */

namespace APP\plugins\generic\customReviewerDecisionsPlugin;

use APP\core\Application;
use APP\plugins\generic\customReviewerDecisionsPlugin\classes\CustomReviewerDecisionsService;
use APP\template\TemplateManager;
use PKP\core\JSONMessage;
use PKP\facades\Locale;
use PKP\linkAction\LinkAction;
use PKP\linkAction\request\AjaxModal;
use PKP\plugins\GenericPlugin;
use PKP\plugins\Hook;

class CustomReviewerDecisionsPlugin extends GenericPlugin
{
    /** @var CustomReviewerDecisionsService|null */
    protected ?CustomReviewerDecisionsService $_service = null;

    /**
     * Get the service instance.
     */
    public function getService(): CustomReviewerDecisionsService
    {
        if ($this->_service === null) {
            $this->_service = new CustomReviewerDecisionsService($this);
        }
        return $this->_service;
    }

    /**
     * @copydoc Plugin::register()
     */
    public function register($category, $path, $mainContextId = null): bool
    {
        $success = parent::register($category, $path, $mainContextId);
        if ($success && $this->getEnabled($mainContextId)) {
            // Hook into TemplateManager to inject variables and assets
            Hook::add('TemplateManager::display', [$this, 'callbackTemplateDisplay']);
            Hook::add('TemplateManager::fetch', [$this, 'callbackTemplateFetch']);

            // Hook into Reviewer Review Form Step 3
            Hook::add('reviewerreviewstep3form::display', [$this, 'callbackStep3Display']);
            Hook::add('pkpreviewerreviewstep3form::display', [$this, 'callbackStep3Display']);
        }
        return $success;
    }

    /**
     * @copydoc Plugin::getDisplayName()
     */
    public function getDisplayName(): string
    {
        return __('plugins.generic.customReviewerDecisionsPlugin.displayName');
    }

    /**
     * @copydoc Plugin::getDescription()
     */
    public function getDescription(): string
    {
        return __('plugins.generic.customReviewerDecisionsPlugin.description');
    }

    /**
     * Provide management action links in the plugin list.
     */
    public function getActions($request, $actionArgs): array
    {
        $actions = parent::getActions($request, $actionArgs);
        if (!$this->getEnabled()) {
            return $actions;
        }

        $router = $request->getRouter();
        $settingsAction = new LinkAction(
            'settings',
            new AjaxModal(
                $router->url(
                    $request,
                    null,
                    null,
                    'manage',
                    null,
                    ['verb' => 'settings', 'plugin' => $this->getName(), 'category' => 'generic']
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
     */
    public function manage($args, $request)
    {
        $context = $request->getContext();
        if (!$context) {
            return new JSONMessage(false, __('common.none'));
        }
        $contextId = (int) $context->getId();
        $service = $this->getService();
        $templateMgr = TemplateManager::getManager($request);

        $verb = (string) $request->getUserVar('verb');

        switch ($verb) {
            case 'settings':
                $decisions = $service->getDecisions($contextId);
                $supportedLocales = (array) $context->getSupportedLocales();
                $primaryLocale = (string) $context->getPrimaryLocale();

                $localeNames = [];
                try {
                    $localeNames = Locale::getFormattedDisplayNames($supportedLocales);
                } catch (\Throwable $t) {
                    foreach ($supportedLocales as $loc) {
                        $localeNames[$loc] = strtoupper($loc);
                    }
                }

                $colorOptions = [
                    'green' => __('plugins.generic.customReviewerDecisionsPlugin.color.green'),
                    'blue' => __('plugins.generic.customReviewerDecisionsPlugin.color.blue'),
                    'orange' => __('plugins.generic.customReviewerDecisionsPlugin.color.orange'),
                    'red' => __('plugins.generic.customReviewerDecisionsPlugin.color.red'),
                    'purple' => __('plugins.generic.customReviewerDecisionsPlugin.color.purple'),
                    'teal' => __('plugins.generic.customReviewerDecisionsPlugin.color.teal'),
                    'darkred' => __('plugins.generic.customReviewerDecisionsPlugin.color.darkred'),
                    'gray' => __('plugins.generic.customReviewerDecisionsPlugin.color.gray'),
                ];

                $router = $request->getRouter();
                $templateMgr->assign([
                    'pluginName' => $this->getName(),
                    'decisions' => $decisions,
                    'supportedLocales' => $supportedLocales,
                    'localeNames' => $localeNames,
                    'primaryLocale' => $primaryLocale,
                    'currentLocale' => Locale::getLocale(),
                    'colorOptions' => $colorOptions,
                    'nextDecisionId' => $service->getNextAvailableId($contextId),
                    'showGuidelines' => (bool) $this->getSetting($contextId, 'showGuidelines', true),
                    'showBadges' => (bool) $this->getSetting($contextId, 'showBadges', true),
                    'customDecisionsCount' => count($decisions),
                    'saveDecisionUrl' => $router->url($request, null, null, 'manage', null, ['verb' => 'saveDecision', 'plugin' => $this->getName(), 'category' => 'generic']),
                    'deleteDecisionUrl' => $router->url($request, null, null, 'manage', null, ['verb' => 'deleteDecision', 'plugin' => $this->getName(), 'category' => 'generic']),
                    'toggleDecisionUrl' => $router->url($request, null, null, 'manage', null, ['verb' => 'toggleDecision', 'plugin' => $this->getName(), 'category' => 'generic']),
                    'resetDefaultsUrl' => $router->url($request, null, null, 'manage', null, ['verb' => 'resetDefaults', 'plugin' => $this->getName(), 'category' => 'generic']),
                    'saveGeneralSettingsUrl' => $router->url($request, null, null, 'manage', null, ['verb' => 'saveGeneralSettings', 'plugin' => $this->getName(), 'category' => 'generic']),
                    'settingsUrl' => $router->url($request, null, null, 'manage', null, ['verb' => 'settings', 'plugin' => $this->getName(), 'category' => 'generic']),
                ]);

                return new JSONMessage(true, $templateMgr->fetch($this->getTemplateResource('settings/settings.tpl')));

            case 'editDecision':
                $id = (int) $request->getUserVar('id');
                $decision = $id > 0 ? $service->getDecisionById($contextId, $id) : null;
                $supportedLocales = (array) $context->getSupportedLocales();
                $primaryLocale = (string) $context->getPrimaryLocale();

                $localeNames = [];
                try {
                    $localeNames = Locale::getFormattedDisplayNames($supportedLocales);
                } catch (\Throwable $t) {
                    foreach ($supportedLocales as $loc) {
                        $localeNames[$loc] = strtoupper($loc);
                    }
                }

                if (!$decision) {
                    $nextId = $service->getNextAvailableId($contextId);
                    $decision = [
                        'id' => $nextId,
                        'key' => 'custom_' . $nextId,
                        'name' => [],
                        'description' => [],
                        'color' => 'blue',
                        'badgeClass' => 'pkp_badge_info',
                        'sequence' => count($service->getDecisions($contextId)) + 1,
                        'enabled' => true,
                        'isDefault' => false,
                    ];
                }

                $colorOptions = [
                    'green' => __('plugins.generic.customReviewerDecisionsPlugin.color.green'),
                    'blue' => __('plugins.generic.customReviewerDecisionsPlugin.color.blue'),
                    'orange' => __('plugins.generic.customReviewerDecisionsPlugin.color.orange'),
                    'red' => __('plugins.generic.customReviewerDecisionsPlugin.color.red'),
                    'purple' => __('plugins.generic.customReviewerDecisionsPlugin.color.purple'),
                    'teal' => __('plugins.generic.customReviewerDecisionsPlugin.color.teal'),
                    'darkred' => __('plugins.generic.customReviewerDecisionsPlugin.color.darkred'),
                    'gray' => __('plugins.generic.customReviewerDecisionsPlugin.color.gray'),
                ];

                $templateMgr->assign([
                    'pluginName' => $this->getName(),
                    'decision' => $decision,
                    'supportedLocales' => $supportedLocales,
                    'localeNames' => $localeNames,
                    'primaryLocale' => $primaryLocale,
                    'currentLocale' => Locale::getLocale(),
                    'colorOptions' => $colorOptions,
                    'isNew' => !($id > 0),
                ]);

                return new JSONMessage(true, $templateMgr->fetch($this->getTemplateResource('settings/decisionEditModal.tpl')));

            case 'saveDecision':
                $id = (int) $request->getUserVar('id');
                $key = (string) $request->getUserVar('key');
                $color = (string) $request->getUserVar('color');
                $sequence = (int) $request->getUserVar('sequence');
                $enabled = (bool) $request->getUserVar('enabled');

                $names = (array) $request->getUserVar('name');
                $descriptions = (array) $request->getUserVar('description');

                $primaryLocale = (string) $context->getPrimaryLocale();
                if (empty($names[$primaryLocale])) {
                    if (!empty($names['en'])) {
                        $names[$primaryLocale] = $names['en'];
                    } elseif (!empty($names['en_US'])) {
                        $names[$primaryLocale] = $names['en_US'];
                    }
                }

                $savedRecord = $service->saveDecision($contextId, [
                    'id' => $id,
                    'key' => $key,
                    'name' => $names,
                    'description' => $descriptions,
                    'color' => $color,
                    'sequence' => $sequence,
                    'enabled' => $enabled,
                ]);

                return new JSONMessage(true, [
                    'status' => 'success',
                    'message' => __('plugins.generic.customReviewerDecisionsPlugin.savedSuccessfully'),
                    'decision' => $savedRecord,
                ]);

            case 'deleteDecision':
                $id = (int) $request->getUserVar('id');
                if ($id > 0) {
                    $service->deleteDecision($contextId, $id);
                }
                return new JSONMessage(true, [
                    'status' => 'success',
                    'message' => __('plugins.generic.customReviewerDecisionsPlugin.deletedSuccessfully'),
                ]);

            case 'toggleDecision':
                $id = (int) $request->getUserVar('id');
                $newStatus = false;
                if ($id > 0) {
                    $newStatus = $service->toggleDecision($contextId, $id);
                }
                return new JSONMessage(true, [
                    'status' => 'success',
                    'enabled' => $newStatus,
                ]);

            case 'reorderDecisions':
                $orderedIds = (array) $request->getUserVar('orderedIds');
                if (!empty($orderedIds)) {
                    $service->reorderDecisions($contextId, $orderedIds);
                }
                return new JSONMessage(true, ['status' => 'success']);

            case 'resetDefaults':
                $service->resetToDefaults($contextId);
                return new JSONMessage(true, [
                    'status' => 'success',
                    'message' => __('plugins.generic.customReviewerDecisionsPlugin.resetSuccessfully'),
                ]);

            case 'saveGeneralSettings':
                $showGuidelines = (bool) $request->getUserVar('showGuidelines');
                $showBadges = (bool) $request->getUserVar('showBadges');

                $this->updateSetting($contextId, 'showGuidelines', $showGuidelines, 'bool');
                $this->updateSetting($contextId, 'showBadges', $showBadges, 'bool');

                return new JSONMessage(true, [
                    'status' => 'success',
                    'message' => __('plugins.generic.customReviewerDecisionsPlugin.settingsSaved'),
                ]);

            default:
                return parent::manage($args, $request);
        }
    }

    /**
     * Hook callback for Step 3 form display.
     */
    public function callbackStep3Display(string $hookName, array $args): bool
    {
        $request = Application::get()->getRequest();
        $context = $request ? $request->getContext() : null;
        if (!$context) return false;

        $templateMgr = TemplateManager::getManager($request);
        $service = $this->getService();
        $locale = Locale::getLocale();

        $options = $service->getLocalizedOptions((int) $context->getId(), $locale, false);
        $templateMgr->assign('reviewerRecommendationOptions', $options);

        return false;
    }

    /**
     * Hook callback for TemplateManager::display.
     */
    public function callbackTemplateDisplay(string $hookName, array $args): bool
    {
        $templateMgr = $args[0];
        $template = $args[1] ?? '';
        $request = Application::get()->getRequest();
        $context = $request ? $request->getContext() : null;
        if (!$context) return false;

        $contextId = (int) $context->getId();
        $baseUrl = $request->getBaseUrl() . '/' . $this->getPluginPath();

        $templateMgr->addStyleSheet(
            'customReviewerDecisionsCss',
            $baseUrl . '/css/customReviewerDecisions.css',
            ['contexts' => ['backend', 'frontend']]
        );

        $templateMgr->addJavaScript(
            'customReviewerDecisionsJs',
            $baseUrl . '/js/customReviewerDecisions.js',
            ['contexts' => ['backend', 'frontend']]
        );

        $service = $this->getService();
        $locale = Locale::getLocale();
        $metadata = $service->getMetadataMap($contextId, $locale);
        $showGuidelines = (bool) $this->getSetting($contextId, 'showGuidelines', true);
        $showBadges = (bool) $this->getSetting($contextId, 'showBadges', true);

        $clientData = [
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
                ['plugin' => $this->getName(), 'category' => 'generic']
            ),
        ];

        $scriptTag = '<script type="text/javascript">var customReviewerDecisionsConfig = ' . json_encode($clientData) . ';</script>';
        $templateMgr->addHeader('customReviewerDecisionsConfig', $scriptTag);

        return false;
    }

    /**
     * Hook callback for TemplateManager::fetch.
     */
    public function callbackTemplateFetch(string $hookName, array $args): bool
    {
        $templateMgr = $args[0];
        $template = (string) ($args[1] ?? '');
        $request = Application::get()->getRequest();
        $context = $request ? $request->getContext() : null;
        if (!$context) return false;

        $contextId = (int) $context->getId();
        $service = $this->getService();
        $locale = Locale::getLocale();

        if (
            str_contains($template, 'reviewerRecommendations.tpl') ||
            str_contains($template, 'step3.tpl') ||
            str_contains($template, 'readReview.tpl') ||
            str_contains($template, 'authorReadReview.tpl')
        ) {
            $options = $service->getLocalizedOptions($contextId, $locale, false);
            $templateMgr->assign('reviewerRecommendationOptions', $options);
        }

        return false;
    }
}

if (!class_exists(\APP\plugins\generic\customReviewerDecisionsPlugin\CustomReviewerDecisionsPluginPlugin::class)) {
    class_alias(
        CustomReviewerDecisionsPlugin::class,
        \APP\plugins\generic\customReviewerDecisionsPlugin\CustomReviewerDecisionsPluginPlugin::class
    );
}

