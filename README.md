# JustBetter - Magento 2 Deployer Plus

* Reliable fully-automated deployments tool for Magento 2.
* Zero downtime deployments on Magento versions >= 2.2
* Automating your deployments is as easy as defining the servers where you want to deploy to.

## Motivation

This project aims to offer a common solution for fully-automated deployments on all versions of Magento 2.
This tool uses the well known [Deployer](https://deployer.org) and adds specific recipes for each Magento 2 version.

## Important Features

* [Deployer](https://deployer.org) code syntax
* Super easy setup
* Deploy to multiple servers
* Zero Downtime (only Magento >= 2.2)
* Build and Deploy artifacts (only Magento >= 2.2)
* Secure rollbacks (only Magento >= 2.2)

## Installation

```
composer require justbetter/magento2-deployer-plus
```

## Setup

### Magento >= 2.1

```
cp <vendor_dir>/justbetter/magento2-deployer-plus/deploy.php.sample_2_1 deploy.php
```

### Magento >= 2.2

```
cp <vendor_dir>/justbetter/magento2-deployer-plus/deploy.php.sample_2_2 deploy.php
```

### Magento >= 2.2.5

```
cp <vendor_dir>/justbetter/magento2-deployer-plus/deploy.php.sample_2_2_5 deploy.php
```

## Usage

### Git deploys:

```
<bin_dir>/dep deploy [<stage>]
```

### Build artifact deploys: (only Magento >= 2.2)

```
<bin_dir>/dep build
<bin_dir>/dep deploy-artifact [<stage>]
```

## Troubleshooting

#### Js translations missing (magento versions >=2.1.3 <2.2.1)

*  **Problem**: Known Magento issue when executing `setup:static-content:deploy` for several languages.

* **Github Issues**:
	* [7862](https://github.com/magento/magento2/issues/7862)
	* [10673](https://github.com/magento/magento2/issues/10673)

* **Solution**: Until that gets fixed in `2.2.1`, the only workaround is to execute `setup:static-content:deploy` individually for each language: 

	```php
	// deploy.php
	 task('files:static_assets', function () {
		run('{{bin/php}} {{magento_bin}} setup:static-content:deploy en_US {{static_deploy_options}}');
		run('{{bin/php}} {{magento_bin}} setup:static-content:deploy de_CH {{static_deploy_options}}');
		run('{{bin/php}} {{magento_bin}} setup:static-content:deploy fr_FR {{static_deploy_options}}');
	 });
	```
	
#### Compilation error

* **Solution**: Increase php `memory_limit` configuration to 728M o 1024M

#### Static deploy error when setting a new template (if config propagation is not used)

* **Problems**:
    * `[LogicException] Unable to load theme by specified key: 'Template'`
    * `@variable` is undefined in file
* **Reason**: If a new template is set, running `setup:upgrade` is required before executing `setup:static-content:deploy`
* **Solution**: Skip `setup:static-content:deploy` first time you deploy the new template:

	1. Temporary disable task `files:static_assets`
	
		```
		// deploy.php
		task('files:static_assets')->onRoles('Skip');
		```
	
	2. Perform a new release
	3. Enable back `files:static_assets` on your `deploy.php` file

		* Remove `task('files:static_assets')->onRoles('Skip');`
		
	4. Manually execute `files:static_assets`
	
		```
		<bin_dir>/dep files:static_assets [<stage>]
		```	
	    
    After that, future deployments will work without issues

## Prerequisites

- PHP >= 8.0
- MAGENTO >= 2.1

## ChangeLog

[CHANGELOG.md](CHANGELOG.md)

## Developers

* [Claudio Ferraro (JustBetter)](https://github.com/jbclaudio)
* [Contributors](https://github.com/justbetter/magento2-deployer-plus/graphs/contributors)

Licence
-------
[GNU General Public License, version 3 (GPLv3)](http://opensource.org/licenses/gpl-3.0)

Copyright
---------
(c) JustBetter B.V. <hello@justbetter.nl>
