<?php
namespace SesTopicSniffer;

use SesTopicSniffer\Config\SesDefine;
use SesTopicSniffer\Shark\RealShark\TopicShark as RealTopicShark;
use SesTopicSniffer\Shark\StandardShark\TopicShark as StandardTopicShark;

/**
 * SesTopicSniffer
 */
class SesTopicSniffer
{
    private $topicMsg;

    /**
     * __construct
     *
     * @param object $snsTopic
     * @author lee <lee@fusic.co.jp>
     */
    function __construct($snsTopic) {
        $this->topicMsg = json_decode($snsTopic, false);
        $this->RealTopicShark = new RealTopicShark($this->getDomain());
        $this->StandardTopicShark = new StandardTopicShark();
    }

    /**
     * getTopicDetailStatus
     *
     * @return array
     * @author lee <lee@fusic.co.jp>
     */
    public function getTopicDetailStatus() {
        $topicMatchMsg = SesDefine::parseRequestMsgForMatching($this->topicMsg);
        return [
            'ses_standard_status' => $this->StandardTopicShark->snatchStatus($topicMatchMsg),   // SES通りの状態
            'fect_topic_status' => $this->RealTopicShark->snatchStatus($topicMatchMsg),         // 実際の状態
            'topic_msg_point_info' => $topicMatchMsg,                                           // TopicMsgの要点
        ];
    }

    /**
     * checkDomain
     *
     * @param string $domain
     * @return array | false
     * @author lee <lee@fusic.co.jp>
     */
    public function checkDomain($domain = null) {
        if(empty($domain)) {
            $domain = $this->getDomain();
        }
        // check MX(mail exchanger) record to the domain
        if(checkdnsrr($domain, "MX")) {
            if (getmxrr($domain, $MXHost))  {
                return $MXHost;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * checkEmailAccount
     *
     * @author lee <lee@fusic.co.jp>
     */
    public function checkEmailAccount() {
        // @todo 実際メールアドレスが存在するかを確認
    }

    /**
     * getDomain
     *
     * @return array
     * @author lee <lee@fusic.co.jp>
     */
    private function getDomain()
    {
        $emailAddress = $this->topicMsg->mail->destination[0];
        $arr = explode("@", $emailAddress);
        return $arr[1];
    }
}
