<?php
namespace SesTopicSniffer;

use SesTopicSniffer\Config\SesDefine;
use SesTopicSniffer\Shark\RealShark\TopicShark as RealTopicShark;
use SesTopicSniffer\Shark\SesStdShark\TopicShark as SesStdTopicShark;

/**
 * SesTopicSniffer
 */
class SesTopicSniffer
{
    const UNDEFINED_BOUNCE_STATUS_CODE = 400;
    const SOFT_BOUNCE_STATUS_CODE = 410;
    const HARD_BOUNCE_STATUS_CODE = 420;

    const COMPLAINT_STATUS_CODE = 500;
    const SOFT_COMPLAINT_STATUS_CODE = 510;
    const HARD_COMPLAINT_STATUS_CODE = 520;

    const DELIVERY_STATUS_CODE = 200;

    private $_RealTopicShark;
    private $_SesStdTopicShark;

    public $sesStdStatus;
    public $realStatus;

    public $topicMsg;

    public $mail;

    /**
     * getInstance
     * return instance after checks
     *
     * @param object $snsTopic
     * @author lee <lee@fusic.co.jp>
     */
    public static function getInstance($snsTopic)
    {
        static $instance = null;

        // check to singleton and require info in to topic msg
        $topicMsg = json_decode($snsTopic, false);
        $topicMatchMsg = SesDefine::parseRequestMsgForMatching($topicMsg);
        if (null === $instance &&
            !empty($topicMatchMsg) &&                   // check the topic msg info
            !empty($topicMsg->mail->destination) &&     // check the email address
            !empty($topicMsg->mail->messageId)) {       // check the message_id
            $instance = new SesTopicSniffer($topicMatchMsg, $topicMsg->mail);
        }

        return $instance;
    }

    /**
     * __clone
     *
     * @author lee <lee@fusic.co.jp>
     */
    private function __clone()
    {
    }

    /**
     * __wakeup
     *
     * @author lee <lee@fusic.co.jp>
     */
    private function __wakeup()
    {
    }

    /**
     * __construct
     *
     * @param object $snsTopic
     * @author lee <lee@fusic.co.jp>
     */
    protected function __construct($topicMatchMsg, $mail) {
        $this->_SesStdTopicShark = new SesStdTopicShark();
        $this->_RealTopicShark = new RealTopicShark($this->_getDomain($mail->destination[0]));

        $this->sesStdStatus = $this->_SesStdTopicShark->snatchStatus($topicMatchMsg);
        $this->realStatus = $this->_RealTopicShark->snatchStatus($topicMatchMsg);
        $this->topicMsg = $topicMatchMsg;
        $this->mail = $mail;
    }

    /**
     * isNotFactOrEqualSesStd
     *
     * @return boolean
     * @author lee <lee@fusic.co.jp>
     */
    public function isNotFactOrEqualSesStd()
    {
        if(empty($this->realStatus['status'])) {
            return true;
        }

        if(!empty($this->realStatus['status'][key($this->sesStdStatus['status'])]) &&
           count($this->realStatus['status']) == 1) {
            return true;
        }

        return false;
    }

    /**
     * checkDomain
     *
     * @param string $domain
     * @return array | false
     * @author lee <lee@fusic.co.jp>
     */
    static function checkDomain($domain)
    {
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
    static function checkEmailAccount()
    {
        // @todo 実際メールアドレスが存在するかを確認
    }

    /**
     * _getDomain
     *
     * @return string
     * @author lee <lee@fusic.co.jp>
     */
    private function _getDomain($emailAddress)
    {
        $arr = explode("@", $emailAddress);
        return $arr[1];
    }
}
