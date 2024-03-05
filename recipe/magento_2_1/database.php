<?php

declare(strict_types=1);

namespace Deployer;

task('database:upgrade', function () {
    run('{{bin/php}} {{release_path}}/{{magento_bin}} setup:db-schema:upgrade --no-interaction');
    run('{{bin/php}} {{release_path}}/{{magento_bin}} setup:db-data:upgrade --no-interaction');
});
