<?php
namespace SesTopicSniffer\Shark\RealShark\Domains;

use SesTopicSniffer\Shark\RealShark\Domains\DomainContainer;

/**
 * DocomoDomain
 */
class DocomoDomain extends DomainContainer
{
    /**
     * __construct
     *
     * @author lee <lee@fusic.co.jp>
     */
    function __construct()
    {
        $this->myDomain = 'docomo.co.jp';

        // append to real confirm data for soft complaint data
        $this->appendRealSoftComplaintTopicMsg([
            'notificationType' => 'Bounce',
            'bounceType' => 'Permanent',
            'bounceSubType' => 'General',
            'bouncedRecipients' => [
                0 => [
                    'action' => 'failed',
                    'status' => '5.3.0'
                ]
            ]
        ]);

        // append to real confirm data for hard complaint data
        $this->appendRealHardComplaintTopicMsg([
            'notificationType' => 'Bounce',
            'bounceType' => 'Permanent',
            'bounceSubType' => 'General',
            'bouncedRecipients' => [
                0 => [
                    'action' => 'failed',
                    'status' => '5.3.0'
                ]
            ]
        ]);

        // append to real confirm data for hard bounce data
        $this->appendRealHardBounceTopicMsg([
            'notificationType' => 'Bounce',
            'bounceType' => 'Permanent',
            'bounceSubType' => 'General',
            'bouncedRecipients' => [
                0 => [
                    'action' => 'failed',
                    'status' => '5.3.0'
                ]
            ]
        ]);

        // append to real confirm data for delivery data
        $this->appendRealDeliveryTopicMsg([
            'notificationType' => 'Delivery'
        ]);
    }
}
