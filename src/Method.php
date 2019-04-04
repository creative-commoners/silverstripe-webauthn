<?php

namespace SilverStripe\WebAuthn;

use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Manifest\ModuleLoader;
use SilverStripe\MFA\Method\Handler\LoginHandlerInterface;
use SilverStripe\MFA\Method\Handler\RegisterHandlerInterface;
use SilverStripe\MFA\Method\MethodInterface;
use SilverStripe\MFA\State\AvailableMethodDetailsInterface;
use SilverStripe\View\Requirements;

class Method implements MethodInterface
{
    /**
     * Get a URL segment for this method. This will be used in URL paths for performing authentication by this method
     *
     * @return string
     */
    public function getURLSegment()
    {
        return 'web-authn';
    }

    /**
     * Return the LoginHandler that is used to start and verify login attempts with this method
     *
     * @return LoginHandlerInterface
     */
    public function getLoginHandler()
    {
        return Injector::inst()->create(LoginHandler::class);
    }

    /**
     * Return the RegisterHandler that is used to perform registrations with this method
     *
     * @return RegisterHandlerInterface
     */
    public function getRegisterHandler()
    {
        return Injector::inst()->create(RegisterHandler::class);
    }

    public function getDetails()
    {
        return Injector::inst()->create(AvailableMethodDetailsInterface::class, $this);
    }

    /**
     * Return a URL to an image to be used as a thumbnail in the MFA login/registration grid for all MFA methods
     *
     * @return string
     */
    public function getThumbnail()
    {
        return ModuleLoader::getModule('silverstripe/webauthn')->getResource('client/dist/images/u2f.svg')->getURL();
    }

    /**
     * Leverage the Requirements API to ensure client requirements are included. This is called just after the base
     * module requirements are specified
     *
     * @return void
     */
    public function applyRequirements()
    {
        Requirements::javascript('silverstripe/webauthn: client/dist/js/bundle.js');
        Requirements::css('silverstripe/webauthn: client/dist/styles/bundle.css');
    }
}
