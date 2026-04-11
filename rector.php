<?php

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withRootFiles()
    ->withPhpVersion(PhpVersion::PHP_84)
    ->withAttributesSets(symfony: true)
    ->withComposerBased(symfony: true)
    ->withPreparedSets(
        symfonyConfigs: true,
        deadCode: true,
        codeQuality: true
    )
;
