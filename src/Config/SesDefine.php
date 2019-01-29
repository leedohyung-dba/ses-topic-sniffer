<?php
namespace SesTopicSniffer\Config;

/**
 * SesDefine
 */
class SesDefine
{
    const TOPIC_NOTIFICATION_BOUNCE_KEY = 'Bounce';
    const TOPIC_NOTIFICATION_COMPLAINT_KEY = 'Complaint';
    const TOPIC_NOTIFICATION_DELIVERY_KEY = 'Delivery';
    const MATCH_BOUNCE_FORMAT = [
        'notificationType' => self::TOPIC_NOTIFICATION_BOUNCE_KEY,
        'bounceType' => '',
        'bounceSubType' => '',
        'bouncedRecipientsStatus' => ''
    ];
    const MATCH_COMPLAINT_FORMAT = [
        'notificationType' => self::TOPIC_NOTIFICATION_COMPLAINT_KEY
    ];
    const MATCH_DELIVERY_FORMAT = [
        'notificationType' => self::TOPIC_NOTIFICATION_DELIVERY_KEY
    ];

    /**
     * parseRequestMsgForMatching
     *
     * @param array $topicMsg
     * @return array | null
     * @author lee <lee@fusic.co.jp>
     */
    public static function parseRequestMsgForMatching($topicMsg)
    {
        if(empty($topicMsg->notificationType)) {
            return null;
        }

        $notificationType = $topicMsg->notificationType;
        if($notificationType == self::TOPIC_NOTIFICATION_BOUNCE_KEY) {
            $parseMsg = self::MATCH_BOUNCE_FORMAT;
            if(empty($topicMsg->bounce->bounceType) ||
               empty($topicMsg->bounce->bounceSubType) ||
               empty($topicMsg->bounce->bouncedRecipients)) {
                return null;
            }
            $parseMsg['bounceType'] = $topicMsg->bounce->bounceType;
            $parseMsg['bounceSubType'] = $topicMsg->bounce->bounceSubType;
            $parseMsg['bouncedRecipientsStatus'] = [];
            foreach ($topicMsg->bounce->bouncedRecipients as $value) {''
                $pushValue = [
                    'action' => isset($value->action) ? $value->action : null,
                    'status' => isset($value->status) ? $value->status : null
                ];
                array_push($parseMsg['bouncedRecipientsStatus'], $pushValue);
            }
            return $parseMsg;

        } else if($notificationType == self::TOPIC_NOTIFICATION_COMPLAINT_KEY) {
            return self::MATCH_COMPLAINT_FORMAT;

        } else if($notificationType == self::TOPIC_NOTIFICATION_DELIVERY_KEY) {
            return self::MATCH_DELIVERY_FORMAT;

        } else {
            return null;
        }
    }

    /**
     * topicMsg
     *
     * @param array $topicMsg
     * @return array | null
     * @author lee <lee@fusic.co.jp>
     */
    public static function parseConfirmMsgForMatching($topicMsg)
    {
        if(empty($topicMsg['notificationType'])) {
            return null;
        }

        $notificationType = $topicMsg['notificationType'];
        if($notificationType == self::TOPIC_NOTIFICATION_BOUNCE_KEY) {
            $parseMsg = self::MATCH_BOUNCE_FORMAT;
            if(empty($topicMsg['bounceType']) ||
               empty($topicMsg['bounceSubType']) ||
               empty($topicMsg['bouncedRecipients'])) {
                return null;
            }
            $parseMsg['bounceType'] = $topicMsg['bounceType'];
            $parseMsg['bounceSubType'] = $topicMsg['bounceSubType'];
            $parseMsg['bouncedRecipientsStatus'] = [];
            foreach ($topicMsg['bouncedRecipients'] as $value) {
                $pushValue = [
                    'action' => $value['action'],
                    'status' => $value['status']
                ];
                array_push($parseMsg['bouncedRecipientsStatus'], $pushValue);
            }
            return $parseMsg;

        } else if($notificationType == self::TOPIC_NOTIFICATION_COMPLAINT_KEY) {
            return self::MATCH_COMPLAINT_FORMAT;

        } else if($notificationType == self::TOPIC_NOTIFICATION_DELIVERY_KEY) {
            return self::MATCH_DELIVERY_FORMAT;

        } else {
            return null;
        }
    }
}
