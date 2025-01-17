<?php

declare(strict_types=1);

namespace Deployer;

set('languages', 'en_US');
set('static_deploy_options', '--exclude-theme=Magento/blank');

task('files:compile', function () {
    run('{{bin/php}} {{magento_bin}} setup:di:compile');
});

task('files:optimize-autoloader', function () {
    run('{{bin/composer}} dump-autoload --no-dev --optimize --apcu');
});

task('files:static_assets', function () {
    run('{{bin/php}} {{magento_bin}} setup:static-content:deploy {{languages}} {{static_deploy_options}}');
});

task('files:permissions', function () {
    cd('{{magento_dir}}');
    run('chmod -R g+w var vendor pub/static pub/media app/etc');
    run('chmod u+x bin/magento');
});

desc('Generate Magento Files');
task('files:generate', [
    'files:compile',
    'files:optimize-autoloader',
    'files:static_assets',
    'files:permissions',
]);
