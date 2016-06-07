# FeatureFlagBundle #

[![Build Status](https://travis-ci.org/Matthimatiker/FeatureFlagBundle.svg?branch=master)](https://travis-ci.org/Matthimatiker/FeatureFlagBundle)
[![Coverage Status](https://coveralls.io/repos/Matthimatiker/FeatureFlagBundle/badge.svg?branch=master&service=github)](https://coveralls.io/github/Matthimatiker/FeatureFlagBundle?branch=master)

This bundle builds on Symfony's sophisticated security system and provides the means to assign features to roles.
It helps you to:

- Change your way of thinking: Do not define *who* can access some functionality, but *what* functionality is available.
- Assign any feature (or role) to guests that are not logged in.
- Make use of your existing Symfony knowledge: Access control works in the same way as in plain Symfony.

## Installation ##

Install the bundle via [Composer](https://getcomposer.org):

    php composer.phar require matthimatiker/feature-flag-bundle

Enable the bundle in your kernel:

    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Matthimatiker\FeatureFlagBundle\MatthimatikerFeatureFlagBundle(),
            // ...
        );
        // ...
    }

## Usage ##

- config
- check
  - use feature wherever role can be used

## Initialization Tasks (remove this block once you are done) ##

- Activate builds in [Travis CI](https://travis-ci.org/)
- Activate repository at [Coveralls](https://coveralls.io)
- Publish at [Packagist](https://packagist.org/)
- Create webhook that pushes repository updates to [Packagist](https://packagist.org/)

## Motivation: Why does this project exist? ##



## Concept ##


## Known Issues ##
