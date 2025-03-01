<?php

$wgMemCachedPersistent = false;

// Set timeout to 500ms (in microseconds)
$wgMemCachedTimeout = 0.5 * 1e6;

$wgObjectCaches['memcached-mem-1'] = [
	'class'                => 'MemcachedPhpBagOStuff',
	'serializer'           => 'php',
	'persistent'           => false,
	'servers'              => [ $wmgCacheSettings['memcached']['server'][1] ],
	// Effectively disable the failure limit (0 is invalid)
	'server_failure_limit' => 1e9,
	// Effectively disable the retry timeout
	'retry_timeout'        => -1,
	'loggroup'             => 'memcached',
	'timeout'              => $wgMemCachedTimeout,
];

$wgObjectCaches['memcached-mem-2'] = [
	'class'                => 'MemcachedPhpBagOStuff',
	'serializer'           => 'php',
	'persistent'           => false,
	'servers'              => [ $wmgCacheSettings['memcached']['server'][0] ],
	// Effectively disable the failure limit (0 is invalid)
	'server_failure_limit' => 1e9,
	// Effectively disable the retry timeout
	'retry_timeout'        => -1,
	'loggroup'             => 'memcached',
	'timeout'              => $wgMemCachedTimeout,
];

$wgMainCacheType = 'memcached-mem-2';
$wgSessionCacheType = 'memcached-mem-2';
$wgSessionsInObjectCache = true;

$wgMessageCacheType = 'memcached-mem-1';
$wgUseLocalMessageCache = true;
$wgParserCacheType = CACHE_DB;
$wgParserCacheExpireTime = 86400 * 7;
$wgLanguageConverterCacheType = 'memcached-mem-1';
$wgInvalidateCacheOnLocalSettingsChange = false;

$jobrunnerSettings = $wmgCacheSettings['jobrunner'];
$wgJobTypeConf['default'] = [
	'class' => 'JobQueueRedis',
	'redisServer' => $jobrunnerSettings['server'],
	'redisConfig' => [
		'connectTimeout' => 2,
		'password' => $jobrunnerSettings['password'],
		'compression' => 'gzip',
	],
	'claimTTL' => 3600,
	'daemonized' => true,
];

if ( PHP_SAPI === 'cli' ) {
	# APC not available in CLI mode
	$wgLanguageConverterCacheType = CACHE_NONE;
}
