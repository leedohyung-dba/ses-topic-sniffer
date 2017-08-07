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
        'Bounce' => [                // notificationType
            'Undetermined' => [             // bounceType
                'Undetermined' => [         // bounceSubType
                    'status_code' => SesTopicSniffer::UNDEFINED_BOUNCE_STATUS_CODE,
                    'status_name' => 'Undetermined',
                    'detail' => "Amazon SES was unable to determine a specific bounce reason."
                ]
            ],
            'Transient' => [                // bounceType
                'General' => [              // bounceSubType
                    'status_code' => SesTopicSniffer::SOFT_BOUNCE_STATUS_CODE,
                    'status_name' => 'SoftBounce',
                    'status_sub_code' => 411,
                    'status_sub_name' => 'General',
                    'detail' => "Amazon SES received a general bounce. You may be able to successfully retry sending to that recipient in the future."
                ],
                'MailboxFull' => [          // bounceSubType
                    'status_code' => SesTopicSniffer::SOFT_BOUNCE_STATUS_CODE,
                    'status_name' => 'SoftBounce',
                    'status_sub_code' => 412,
                    'status_sub_name' => 'MailboxFull',
                    'detail' => "Amazon SES received a mailbox full bounce. You may be able to successfully retry sending to that recipient in the future."
                ],
                'MessageTooLarge' => [      // bounceSubType
                    'status_code' => SesTopicSniffer::SOFT_BOUNCE_STATUS_CODE,
                    'status_name' => 'SoftBounce',
                    'status_sub_code' => 413,
                    'status_sub_name' => 'MessageTooLarge',
                    'detail' => "Amazon SES received a message too large bounce. You may be able to successfully retry sending to that recipient if you reduce the message size."
                ],
                'ContentRejected' => [      // bounceSubType
                    'status_code' => SesTopicSniffer::SOFT_BOUNCE_STATUS_CODE,
                    'status_name' => 'SoftBounce',
                    'status_sub_code' => 414,
                    'status_sub_name' => 'ContentRejected',
                    'detail' => "Amazon SES received a content rejected bounce. You may be able to successfully retry sending to that recipient if you change the message content."
                ],
                'AttachmentRejected' => [   // bounceSubType
                    'status_code' => SesTopicSniffer::SOFT_BOUNCE_STATUS_CODE,
                    'status_name' => 'SoftBounce',
                    'status_sub_code' => 415,
                    'status_sub_name' => 'AttachmentRejected',
                    'detail' => "Amazon SES received an attachment rejected bounce. You may be able to successfully retry sending to that recipient if you remove or change the attachment."
                ],
            ],
            'Permanent' => [                // bounceType
                'General' => [              // bounceSubType
                    'status_code' => SesTopicSniffer::HARD_BOUNCE_STATUS_CODE,
                    'status_name' => 'HardBounce',
                    'status_sub_code' => 421,
                    'status_sub_name' => 'General',
                    'detail' => "Amazon SES received a general hard bounce and recommends that you remove the recipient's email address from your mailing list."
                ],
                'NoEmail' => [              // bounceSubType
                    'status_code' => SesTopicSniffer::HARD_BOUNCE_STATUS_CODE,
                    'status_name' => 'HardBounce',
                    'status_sub_code' => 422,
                    'status_sub_name' => 'NoEmail',
                    'detail' => "Amazon SES received a permanent hard bounce because the target email address does not exist. It is recommended that you remove that recipient from your mailing list."
                ],
                'Suppressed' => [           // bounceSubType
                    'status_code' => SesTopicSniffer::HARD_BOUNCE_STATUS_CODE,
                    'status_name' => 'HardBounce',
                    'status_sub_code' => 423,
                    'status_sub_name' => 'Suppressed',
                    'detail' => "Amazon SES has suppressed sending to this address because it has a recent history of bouncing as an invalid address."
                ],
            ],
        ]
    ];

    // aws complaint status for Topic message
    const TOPIC_COMPLAINT_MSG_FIND = [
        'Complaint' => [                // notificationType
            'status_code' => SesTopicSniffer::COMPLAINT_STATUS_CODE,
            'status_name' => 'Complaint',
            'detail' => 'This field is present only if the notificationType is Complaint and contains a JSON object that holds information about the complaint. '
        ]
    ];

    // aws delivery status for Topic message
    const TOPIC_DELIVERY_MSG_FIND = [
        'Delivery' => [                 // notificationType
            'status_code' => SesTopicSniffer::DELIVERY_STATUS_CODE,
            'status_name' => 'Delivery',
            'detail' => 'This field is present only if the notificationType is Delivery and contains a JSON object that holds information about the delivery.'
        ]
    ];

    // undefined
    const UNDEFINED_STATUS = [
        'status_code' => '0',
        'status_name' => 'Undefined',
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