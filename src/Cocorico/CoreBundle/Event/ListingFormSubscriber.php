<?php

/*
 * This file is part of the Cocorico package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocorico\CoreBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ListingFormSubscriber implements EventSubscriberInterface
{
    /**
     * Transfer funds from asker to offerer wallet
     *
     * @param BookingValidateEvent $event
     *
     * @return bool
     * @throws \Exception
     */
    public function onListingNewFormBuild(ListingFormBuilderEvent $listingFormBuilderEvent)
    {
        $form = $listingFormBuilderEvent->getFormBuilder();

        $form->add(
            'isbn',
            TextType::class,
            [
                'label'      => 'ISBN',
                'mapped'     => false,
                'block_name' => 'location'
            ]
        );
    }

    public static function getSubscribedEvents()
    {
        return array(
            ListingFormEvents::LISTING_NEW_FORM_BUILD => array( 'onListingNewFormBuild', 1 ),
        );
    }
}
