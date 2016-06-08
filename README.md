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

### Configure Features ###

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

The ``security.yml`` is the central place to map features to roles.
To distinguish features from roles, they are prefixed with ``FEATURE_*``.

#### Grouping Features ####

- normal users
- admins

#### Features For All Users ####

permissions IS_AUTHENTICATED_ANONYMOUSLY

#### Features Only For Guests ####

ROLE_ANONYMOUS

### Access Control ###

Access should be checked against the features using the existing mechanisms in Symfony. 
Of course you can also still check access against roles, although it should not be necessary.

#### Controller Actions ####

You can check access to contoller actions in the [same way as with roles](http://symfony.com/doc/current/book/security.html#securing-controllers-and-other-code):

    /**
     * @Security("has_role('FEATURE_NEWSLETTER_REGISTRATION')")
     */
    public function myAction($name)
    {
        // ...
    }
    
#### Twig Templates #### 

Within your Twig templates, you can use the [is_granted() function](http://symfony.com/doc/current/book/security.html#access-control-in-templates):

    {% if is_granted('FEATURE_NEWSLETTER_REGISTRATION') %}
        <a href="...">Register now</a>
    {% endif %}

#### Other Areas ####

You can check against features anywhere you used roles before, for example in the
[access_control section](http://symfony.com/doc/current/book/security.html#securing-url-patterns-access-control)
of your ``security.yml``, together with Symfony's
[controller helper methods](http://symfony.com/doc/current/book/security.html#securing-controllers-and-other-code)
or directly with the
[security.authorization_checker](http://symfony.com/doc/current/book/security.html#securing-controllers-and-other-code)
service.

## Initialization Tasks (remove this block once you are done) ##

- Activate builds in [Travis CI](https://travis-ci.org/)
- Activate repository at [Coveralls](https://coveralls.io)
- Publish at [Packagist](https://packagist.org/)
- Create webhook that pushes repository updates to [Packagist](https://packagist.org/)

## Motivation: Why does this project exist? ##



## Concept ##


## Known Issues ##
