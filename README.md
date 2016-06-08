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

Features are assigned to roles. You connect features and roles in your ``security.yml``
via Symfony's well known [hierarchical roles](http://symfony.com/doc/current/book/security.html#hierarchical-roles):

    security:
        # ...
        
        role_hierarchy:
            # Start with a role and assign the accessible features.
            ROLE_USER:
                - FEATURE_BLOG
                
            # You can organize your features in groups and assign sub-features.
            # These are inherited just like roles.
            FEATURE_BLOG:
                - FEATURE_BLOG_WRITE
                - FEATURE_BLOG_READ
                
            # Features may depend on IS_AUTHENTICATED_* permissions.
            # That is useful to assign features to all visitors (logged in or not):
            IS_AUTHENTICATED_ANONYMOUSLY:
                - FEATURE_NEWSLETTER_REGISTRATION
                
            # The role ROLE_ANONYMOUS is used to assign features to visitors that are not logged in.
            ROLE_ANONYMOUS:
                - FEATURE_LOGIN

central assignment
can still check roles

``FEATURE_*``

- config
- check
  - use feature wherever role can be used
  - controller
  - Twig

## Initialization Tasks (remove this block once you are done) ##

- Activate builds in [Travis CI](https://travis-ci.org/)
- Activate repository at [Coveralls](https://coveralls.io)
- Publish at [Packagist](https://packagist.org/)
- Create webhook that pushes repository updates to [Packagist](https://packagist.org/)

## Motivation: Why does this project exist? ##



## Concept ##


## Known Issues ##
