<?php

/*
 * This file is part of the Cocorico package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocorico\MessageBundle\Event;

use Cocorico\CoreBundle\Entity\Booking;
use Cocorico\CoreBundle\Event\BookingEvent;
use Cocorico\CoreBundle\Event\BookingEvents;
use Cocorico\MessageBundle\Model\ThreadManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Cocorico\CoreBundle\Mailer\TwigSwiftMailer;

class BookingSubscriber implements EventSubscriberInterface
{
    protected $threadManager;

    protected $twigSwiftMailer;

    protected const MAIL_SUBJECT = 'New Booking Created';

    /**
     * @param ThreadManager $threadManager
     */
    public function __construct(ThreadManager $threadManager, TwigSwiftMailer $twigSwiftMailer)
    {
        $this->threadManager = $threadManager;
        $this->twigSwiftMailer = $twigSwiftMailer;
    }


    public function onBookingNewCreated(BookingEvent $event)
    {
        $booking = $event->getBooking();
        $user = $booking->getUser();
        $this->sendMessageToAdmin($booking, $user);
        $this->threadManager->createNewListingThread($user, $booking);
    }


    public static function getSubscribedEvents()
    {
        return array(
            BookingEvents::BOOKING_NEW_CREATED => array('onBookingNewCreated', 1),
        );
    }

    /**
     * @param Booking $booking
     */
    private function sendMessageToAdmin(Booking $booking)
    {
        $subject = self::MAIL_SUBJECT;
        $body = sprintf('Created new booking id %s', $booking->getId());
        $this->twigSwiftMailer->sendMessageToAdmin($subject, $body);
    }
}
