<?php
namespace SesTopicSniffer\Shark\SesStdShark;

use SesTopicSniffer\Config\SesDefine;
use SesTopicSniffer\Shark\SharkContainer;
use SesTopicSniffer\SesTopicSniffer;

/**
 * TopicShark
 */
class TopicShark implements SharkContainer
{
    // aws bounce status for Topic message
    const TOPIC_BOUNCE_MSG_FIND = [
        'Bounce' => [                       // notificationType
            'Undetermined' => [             // bounceType
                'Undetermined' => [         // bounceSubType
                    'status' => [
                        SesTopicSniffer::UNDEFINED_BOUNCE_STATUS_CODE => 'Undetermined'
                    ],
                    'detail' => "Amazon SES was unable to determine a specific bounce reason."
                ]
            ],
            'Transient' => [                // bounceType
                'General' => [              // bounceSubType
                    'status' => [
                        SesTopicSniffer::SOFT_BOUNCE_STATUS_CODE => 'SoftBounce'
                    ],
                    'status_sub' => [
                        411 => 'General'
                    ],
                    'detail' => "Amazon SES received a general bounce. You may be able to successfully retry sending to that recipient in the future."
                ],
                'MailboxFull' => [          // bounceSubType
                    'status' => [
                        SesTopicSniffer::SOFT_BOUNCE_STATUS_CODE => 'SoftBounce'
                    ],
                    'status_sub' => [
                        412 => 'MailboxFull'
                    ],
                    'detail' => "Amazon SES received a mailbox full bounce. You may be able to successfully retry sending to that recipient in the future."
                ],
                'MessageTooLarge' => [      // bounceSubType
                    'status' => [
                        SesTopicSniffer::SOFT_BOUNCE_STATUS_CODE => 'SoftBounce'
                    ],
                    'status_sub' => [
                        413 => 'MessageTooLarge'
                    ],
                    'detail' => "Amazon SES received a message too large bounce. You may be able to successfully retry sending to that recipient if you reduce the message size."
                ],
                'ContentRejected' => [      // bounceSubType
                    'status' => [
                        SesTopicSniffer::SOFT_BOUNCE_STATUS_CODE => 'SoftBounce'
                    ],
                    'status_sub' => [
                        414 => 'ContentRejected'
                    ],
                    'detail' => "Amazon SES received a content rejected bounce. You may be able to successfully retry sending to that recipient if you change the message content."
                ],
                'AttachmentRejected' => [   // bounceSubType
                    'status' => [
                        SesTopicSniffer::SOFT_BOUNCE_STATUS_CODE => 'SoftBounce'
                    ],
                    'status_sub' => [
                        415 => 'AttachmentRejected'
                    ],
                    'detail' => "Amazon SES received an attachment rejected bounce. You may be able to successfully retry sending to that recipient if you remove or change the attachment."
                ],
            ],
            'Permanent' => [                // bounceType
                'General' => [              // bounceSubType
                    'status' => [
                        SesTopicSniffer::HARD_BOUNCE_STATUS_CODE => 'HardBounce'
                    ],
                    'status_sub' => [
                        421 => 'General'
                    ],
                    'detail' => "Amazon SES received a general hard bounce and recommends that you remove the recipient's email address from your mailing list."
                ],
                'NoEmail' => [              // bounceSubType
                    'status' => [
                        SesTopicSniffer::HARD_BOUNCE_STATUS_CODE => 'HardBounce'
                    ],
                    'status_sub' => [
                        422 => 'NoEmail'
                    ],
                    'detail' => "Amazon SES received a permanent hard bounce because the target email address does not exist. It is recommended that you remove that recipient from your mailing list."
                ],
                'Suppressed' => [           // bounceSubType
                    'status' => [
                        SesTopicSniffer::HARD_BOUNCE_STATUS_CODE => 'HardBounce'
                    ],
                    'status_sub' => [
                        423 => 'Suppressed'
                    ],
                    'detail' => "Amazon SES has suppressed sending to this address because it has a recent history of bouncing as an invalid address."
                ],
                'OnAccountSuppressionList' => [ // bounceSubType
                    'status' => [
                        SesTopicSniffer::HARD_BOUNCE_STATUS_CODE => 'HardBounce'
                    ],
                    'status_sub' => [
                        424 => 'OnAccountSuppressionList'
                    ],
                    'detail' => "Amazon SES has suppressed sending to this address because it is on the account-level suppression list."
                ],
            ],
        ]
    ];

    // aws complaint status for Topic message
    const TOPIC_COMPLAINT_MSG_FIND = [
        'Complaint' => [                // notificationType
            'status' => [
                SesTopicSniffer::COMPLAINT_STATUS_CODE => 'Complaint'
            ],
            'detail' => 'This field is present only if the notificationType is Complaint and contains a JSON object that holds information about the complaint. '
        ]
    ];

    // aws delivery status for Topic message
    const TOPIC_DELIVERY_MSG_FIND = [
        'Delivery' => [                 // notificationType
            'status' => [
                SesTopicSniffer::DELIVERY_STATUS_CODE => 'Delivery'
            ],
            'detail' => 'This field is present only if the notificationType is Delivery and contains a JSON object that holds information about the delivery.'
        ]
    ];

    // undefined
    const UNDEFINED_STATUS = [
        'status' => [
            '0' => 'Undefined'
        ],
        'detail' => 'notting match of case to topic message'
    ];

    /**
     * snatchStatus
     *
     * @param array $topicMatchMsg
     * @return array
     * @author lee <lee@fusic.co.jp>
     */
    public function snatchStatus($topicMatchMsg)
    {
        if(empty($topicMatchMsg)) {
            return self::UNDEFINED_STATUS;
        }

        $status = $this->getMatchStatus($topicMatchMsg);

        if(empty($status)) {
            return self::UNDEFINED_STATUS;
        }

        return $status;
    }

    /**
     * getMatchStatus
     *
     * @param array $topicMatchMsg
     * @return array | null
     * @author lee <lee@fusic.co.jp>
     */
    private function getMatchStatus($topicMatchMsg) {
        $notificationType = $topicMatchMsg['notificationType'];
        if(!empty(self::TOPIC_BOUNCE_MSG_FIND[$notificationType])) {
            $bounceType = $topicMatchMsg['bounceType'];
            $bounceSubType = $topicMatchMsg['bounceSubType'];
            return self::TOPIC_BOUNCE_MSG_FIND[$notificationType][$bounceType][$bounceSubType];
        } else if(!empty(self::TOPIC_COMPLAINT_MSG_FIND[$notificationType])) {
            return self::TOPIC_COMPLAINT_MSG_FIND[$notificationType];
        } else if(!empty(self::TOPIC_DELIVERY_MSG_FIND[$notificationType])) {
            return self::TOPIC_DELIVERY_MSG_FIND[$notificationType];
        } else {
            return null;
        }
    }
}
