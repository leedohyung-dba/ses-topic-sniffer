<?php
namespace SesTopicSniffer\Shark\RealShark\Domains;

use SesTopicSniffer\Shark\RealShark\Domains\DomainContainer;

/**
 * GmailDomain
 */
class GmailDomain extends DomainContainer
{
    /**
     * __construct
     *
     * @author lee <lee@fusic.co.jp>
     */
    function __construct()
    {
        $this->myDomain = 'gmail.com';

        // append to real confirm data for hard bounce data
        $this->appendRealHardBounceTopicMsg([
            'notificationType' => 'Bounce',
            'bounceType' => 'Permanent',
            'bounceSubType' => 'General',
            'bouncedRecipients' => [
                0 => [
                    'action' => 'failed',
                    'status' => '5.1.1'
                ]
            ]
        ]);

        // append to real confirm data for delivery data
        $this->appendRealDeliveryTopicMsg([
            'notificationType' => 'Delivery'
        ]);
    }
}
