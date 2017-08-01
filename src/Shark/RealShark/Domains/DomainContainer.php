<?php
namespace SesTopicSniffer\Shark\RealShark\Domains;

use SesTopicSniffer\Config\SesDefine;

/**
 * DomainContainer
 */
abstract class DomainContainer
{
    protected $myDomain;
    protected $realSoftBounceTopicMsgs = [];
    protected $realHardBounceTopicMsgs = [];
    protected $realSoftComplaintTopicMsgs = [];
    protected $realHardComplaintTopicMsgs = [];
    protected $realDeliveryTopicMsgs = [];
    protected $myDomainComment = [];

    /**
     * appendRealSoftComplaintTopicMsg
     *
     * @param array $topicMsg
     * @return boolean
     * @author lee <lee@fusic.co.jp>
     */
    protected function appendRealSoftComplaintTopicMsg($topicMsg)
    {
        $appendMsg = SesDefine::parseConfirmMsgForMatching($topicMsg);
        if(empty($appendMsg)){
            return false;
        }
        array_push($this->realSoftComplaintTopicMsgs, $appendMsg);
        return true;
    }

    /**
     * appendRealHardComplaintTopicMsg
     *
     * @param array $topicMsg
     * @return boolean
     * @author lee <lee@fusic.co.jp>
     */
    protected function appendRealHardComplaintTopicMsg($topicMsg)
    {
        $appendMsg = SesDefine::parseConfirmMsgForMatching($topicMsg);
        if(empty($appendMsg)){
            return false;
        }
        array_push($this->realHardComplaintTopicMsgs, $appendMsg);
        return true;
    }

    /**
     * appendRealSoftBounceTopicMsg
     *
     * @param array $topicMsg
     * @return boolean
     * @author lee <lee@fusic.co.jp>
     */
    protected function appendRealSoftBounceTopicMsg($topicMsg)
    {
        $appendMsg = SesDefine::parseConfirmMsgForMatching($topicMsg);
        if(empty($appendMsg)){
            return false;
        }
        array_push($this->realSoftBounceTopicMsgs, $appendMsg);
        return true;
    }

    /**
     * appendRealHardBounceTopicMsg
     *
     * @param array $topicMsg
     * @return boolean
     * @author lee <lee@fusic.co.jp>
     */
    protected function appendRealHardBounceTopicMsg($topicMsg)
    {
        $appendMsg = SesDefine::parseConfirmMsgForMatching($topicMsg);
        if(empty($appendMsg)){
            return false;
        }
        array_push($this->realHardBounceTopicMsgs, $appendMsg);
        return true;
    }

    /**
     * appendRealDeliveryTopicMsg
     *
     * @param array $topicMsg
     * @return boolean
     * @author lee <lee@fusic.co.jp>
     */
    protected function appendRealDeliveryTopicMsg($topicMsg)
    {
        $appendMsg = SesDefine::parseConfirmMsgForMatching($topicMsg);
        if(empty($appendMsg)){
            return false;
        }
        array_push($this->realDeliveryTopicMsgs, $appendMsg);
        return true;
    }

    /**
     * matchIsRealSoftComplaint
     *
     * @param array $topicMsg
     * @return boolean
     * @author lee <lee@fusic.co.jp>
     */
    public function matchIsRealSoftComplaint($topicMsg)
    {
        foreach ($this->realSoftComplaintTopicMsgs as $value) {
            if($topicMsg === $value) {
                return true;
            }
        }
        return false;
    }

    /**
     * matchIsRealHardComplaint
     *
     * @param array $topicMsg
     * @return boolean
     * @author lee <lee@fusic.co.jp>
     */
    public function matchIsRealHardComplaint($topicMsg)
    {
        foreach ($this->realHardComplaintTopicMsgs as $value) {
            if($topicMsg === $value) {
                return true;
            }
        }
        return false;
    }

    /**
     * matchIsRealSoftBounce
     *
     * @param array $topicMsg
     * @return boolean
     * @author lee <lee@fusic.co.jp>
     */
    public function matchIsRealSoftBounce($topicMsg)
    {
        foreach ($this->realSoftBounceTopicMsgs as $value) {
            if($topicMsg === $value) {
                return true;
            }
        }
        return false;
    }

    /**
     * matchIsRealHardBounce
     *
     * @param array $topicMsg
     * @return boolean
     * @author lee <lee@fusic.co.jp>
     */
    public function matchIsRealHardBounce($topicMsg)
    {
        foreach ($this->realHardBounceTopicMsgs as $value) {
            if($topicMsg === $value) {
                return true;
            }
        }
        return false;
    }

    /**
     * matchIsRealDelivery
     *
     * @param array $topicMsg
     * @return boolean
     * @author lee <lee@fusic.co.jp>
     */
    public function matchIsRealDelivery($topicMsg)
    {
        foreach ($this->realDeliveryTopicMsgs as $value) {
            if($topicMsg === $value) {
                return true;
            }
        }
        return false;
    }
}
