# FeatureFlagBundle #

[![Build Status](https://travis-ci.org/Matthimatiker/FeatureFlagBundle.svg?branch=master)](https://travis-ci.org/Matthimatiker/FeatureFlagBundle)
[![Coverage Status](https://coveralls.io/repos/github/Matthimatiker/FeatureFlagBundle/badge.svg?branch=master)](https://coveralls.io/github/Matthimatiker/FeatureFlagBundle?branch=master)

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

Like normal roles in the hierarchy, features can be grouped. This allows you to compose new features from sub-features:

    FEATURE_BLOG_USER:
        - FEATURE_BLOG_READ
        - FEATURE_BLOG_WRITE
    
    FEATURE_BLOG_ADMIN:
        - FEATURE_BLOG_USER
        - FEATURE_BLOG_DELETE
        
In the example above, any user that has the permission ``FEATURE_BLOG_ADMIN`` will also inherit the permissions
``FEATURE_BLOG_WRITE``, ``FEATURE_BLOG_WRITE`` and ``FEATURE_BLOG_DELETE``. Composing features in this way 
guarantees fine-grained permission control. Want to prevent the deletion of blog posts? Just remove 
``FEATURE_BLOG_DELETE`` from the hierarchy.

#### Features For All Users ####

In plain Symfony, you cannot assign roles to anonymous users. This bundle overcomes that limitation by allowing the
assignment of roles and features to Symfony's ``IS_AUTHENTICATED_*`` permissions in the hierarchy.

Be aware that the existing [rules for the ``IS_AUTHENTICATED_*`` permissions](http://symfony.com/doc/current/book/security.html#checking-to-see-if-a-user-is-logged-in-is-authenticated-fully) apply. 
For example ``IS_AUTHENTICATED_ANONYMOUSLY`` is available to *all* visitors, guests as well as logged in users. 
This means that *any* feature assigned to ``IS_AUTHENTICATED_ANONYMOUSLY`` will be available to *everyone*.

#### Features Only For Guests ####

With standard Symfony it is not possible to assign roles to users that are not logged in.
This bundle removes that limitation by introducing the special role ``ROLE_ANONYMOUS``.
``ROLE_ANONYMOUS`` is *only* assigned to anonymous users. 
Once a user logs in, she will lose that role.

``ROLE_ANONYMOUS`` can be used in the [role hierarchy configuration](http://symfony.com/doc/current/book/security.html#hierarchical-roles)
to assign features to guests:

    ROLE_ANONYMOUS:
        - FEATURE_LOGIN

Managing guest features in the role hierarchy allows you to enable and disable these features via simple
configuration change.

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

- Activate repository at [Coveralls](https://coveralls.io)
- Publish at [Packagist](https://packagist.org/)
- Create webhook that pushes repository updates to [Packagist](https://packagist.org/)

## Known Issues and Limitations ##

This bundle deals with managing access to features via security configuration, so a permission change also requires
a configuration change.
It does *not* provide means to enable and disable features on the fly, although that should be possible via
[dynamic roles](http://php-and-symfony.matthiasnoback.nl/2012/07/symfony2-security-creating-dynamic-roles-using-roleinterface/)
(just to mention one approach).
However, on-the-fly feature management is out of scope for this bundle as it is a very application specific topic.
