<?php

import('lib.pkp.classes.plugins.BlockPlugin');

class RealTestBlockPlugin extends BlockPlugin {
	var $_parentPlugin;

	function register($category, $path, $mainContextId = null) {
		if (parent::register($category, $path, $mainContextId)) {
			if (!Config::getVar('general', 'installed') || defined('RUNNING_UPGRADE')) return true;

			if ($this->getEnabled($mainContextId)) {
				HookRegistry::register('TemplateManager::display', array($this, 'handleTemplateDisplay'));
				HookRegistry::register('LoadHandler', array($this, 'setupCallbackHandler'));
			}
			return true;
		}
		return false;
	}

	function handleTemplateDisplay($hookName, $args) {
		$templateMgr =& $args[0];
		$template =& $args[1];
		// $request = PKPApplication::getRequest();
		$request = PKPApplication::get()->getRequest();
		$templateMgr->addStyleSheet(
			'TestBlockPlugin',
			$request->getBaseUrl() . '/' . $this->getStyleSheet(),
			array(
				'contexts' => array('frontend')
			)
		);
		return false;
	}

	function setupCallbackHandler($hookName, $args) {
		// TODO: Do something
	}

	function getStyleSheet() {
		return $this->getPluginPath() . '/styles/teststyle.css';
	}

	function __construct($parentPlugin) {
		$this->_parentPlugin = $parentPlugin;
		parent::__construct();
	}
	function getManagerPlugin() {
		return $this->_parentPlugin;
	}
	function getPluginPath() {
		$plugin = $this->getManagerPlugin();
		return $plugin->getPluginPath();
	}
	function getTemplatePath($inCore = false) {
		$plugin = $this->getManagerPlugin();
		return $plugin->getTemplatePath($inCore);
	}
	function getHideManagement() {
		return true;
	}
	function getEnabled($contextId = null) {
		if (!Config::getVar('general', 'installed')) return true;
		return parent::getEnabled($contextId);
	}
	function getDisplayName() {
		return 'Real Test Block Plugin';
	}
	function getDescription() {
		return 'Real Test Block Plugin Description';
	}
	function getContents($templateMgr, $request = null) {
		return parent::getContents($templateMgr, $request);
	}
}

