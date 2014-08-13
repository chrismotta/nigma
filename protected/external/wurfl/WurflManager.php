<?php

class WurflManager {

	public static function loadWurfl() {

		$wurflDir = dirname(__FILE__) . '/WURFL';
		$resourcesDir = dirname(__FILE__) . '/../../data/wurfl';

		require_once $wurflDir.'/Application.php';

		$persistenceDir = $resourcesDir.'/storage/persistence';
		$cacheDir = $resourcesDir.'/storage/cache';

		// Create WURFL Configuration
		$wurflConfig = new WURFL_Configuration_InMemoryConfig();

		// Set location of the WURFL File
		$wurflConfig->wurflFile($resourcesDir.'/wurfl.zip');

		// Set the match mode for the API ('performance' or 'accuracy')
		$wurflConfig->matchMode('performance');

		// Automatically reload the WURFL data if it changes
		$wurflConfig->allowReload(true);

		/*
		// Optionally specify which capabilities should be loaded
		// This is disabled by default as it would cause the demo/index.php
		// page to fail due to missing capabilities
		$wurflConfig->capabilityFilter(array(
	        "device_os",
	        "device_os_version",
	        "is_tablet",
	        "is_wireless_device",
	        "mobile_browser",
	        "mobile_browser_version",
	        "pointing_method",
	        "preferred_markup",
	        "resolution_height",
	        "resolution_width",
	        "ux_full_desktop",
	        "xhtml_support_level",
		));
		*/

		// Setup WURFL Persistence
		$wurflConfig->persistence('file', array('dir' => $persistenceDir));

		// Setup Caching
		$wurflConfig->cache('file', array('dir' => $cacheDir, 'expiration' => 36000));

		// Create a WURFL Manager Factory from the WURFL Configuration
		$wurflManagerFactory = new WURFL_WURFLManagerFactory($wurflConfig);

		// Create a WURFL Manager
		/* @var $wurflManager WURFL_WURFLManager */
		$wurflManager = $wurflManagerFactory->create();

		return $wurflManager;

	}
}