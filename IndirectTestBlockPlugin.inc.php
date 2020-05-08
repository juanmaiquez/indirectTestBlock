<?php

import('lib.pkp.classes.plugins.GenericPlugin');

class IndirectTestBlockPlugin extends GenericPlugin {
	function getDisplayName() {
		return 'Indirect Test Block Plugin';
	}
	function getDescription() {
		return 'Indirect Test Block Plugin Description';
	}

	function register($category, $path, $mainContextId = null) {
		if (parent::register($category, $path, $mainContextId)) {
			if (!Config::getVar('general', 'installed') || defined('RUNNING_UPGRADE')) return true;

			$this->import('RealTestBlockPlugin');
			if ($this->getEnabled($mainContextId)) {
				if ($request = Application::get()->getRequest()) {
					if ($mainContextId) {
						$contextId = $mainContextId;
					} else {
						$context = $request->getContext();
						$contextId = $context ? $context->getId() : CONTEXT_SITE;
					}
					$realPlugin = new RealTestBlockPlugin($this);
					PluginRegistry::register(
						'blocks',
						$realPlugin,
						$this->getPluginPath()
					);
					$realPlugin->setEnabled(true);
				}
			} else {
				// $realPlugin = new RealTestBlockPlugin($this);
				// $realPlugin->setEnabled(false);
			}
			return true;
		}
		return false;
	}
	function isSitePlugin() {
		return !Application::get()->getRequest()->getContext();
	}
}
