<?php
namespace SesTopicSniffer\Shark\RealShark;

use SesTopicSniffer\Shark\SharkContainer;
use SesTopicSniffer\Shark\RealShark\Domains\DomainFactory;
use SesTopicSniffer\SesTopicSniffer;

/**
 * TopicShark
 */
class TopicShark implements SharkContainer
{
    const BOUNCE_STATUS = [
        'status_code' => SesTopicSniffer::UNDEFINED_BOUNCE_STATUS_CODE,
        'status_name' => 'Bounce'
    ];

    const SOFT_BOUNCE_STATUS = [
        'status_code' => SesTopicSniffer::SOFT_BOUNCE_STATUS_CODE,
        'status_name' => 'SoftBounce'
    ];

    const HARD_BOUNCE_STATUS = [
        'status_code' => SesTopicSniffer::HARD_BOUNCE_STATUS_CODE,
        'status_name' => 'HardBounce'
    ];

    const COMPLAINT_STATUS = [
        'status_code' => SesTopicSniffer::COMPLAINT_STATUS_CODE,
        'status_name' => 'Complaint'
    ];

    const SOFT_COMPLAINT_STATUS = [
        'status_code' => SesTopicSniffer::SOFT_COMPLAINT_STATUS_CODE,
        'status_name' => 'SoftComplaint'
    ];

    const HARD_COMPLAINT_STATUS = [
        'status_code' => SesTopicSniffer::HARD_COMPLAINT_STATUS_CODE,
        'status_name' => 'HardComplaint'
    ];

    const DELIVERY_STATUS = [
        'status_code' => SesTopicSniffer::DELIVERY_STATUS_CODE,
        'status_name' => 'Delivery'
    ];

    const UNDEFINED_STATUS = [
        'status_code' => 0,
        'status_name' => 'Undefined'
    ];

    const RESULT_FORMAT = [
        'status' => [],
        'detail' => 'notting match of case to topic message'
    ];

    private $factory;

    /**
     * __construct
     *
     * @param string $domain
     * @author lee <lee@fusic.co.jp>
     */
    function __construct($domain) {
        $this->factory = new DomainFactory();
        $this->Domain = $this->factory->myDomain($domain);
    }

    /**
     * snatchStatus
     *
     * @param array $topicMatchMsg
     * @return array
     * @author lee <lee@fusic.co.jp>
     */
    public function snatchStatus($topicMatchMsg)
    {
        $status = self::RESULT_FORMAT;

        if(empty($topicMatchMsg)) {
            return $status;
        }

        if($this->Domain->matchIsRealSoftBounce($topicMatchMsg)) {
            $status['status'][self::SOFT_BOUNCE_STATUS['status_code']] = self::SOFT_BOUNCE_STATUS['status_name'];
            $status['detail'] = 'This is a confirmed status of the SoftBounce.';
        }

        if($this->Domain->matchIsRealHardBounce($topicMatchMsg)) {
            $status['status'][self::HARD_BOUNCE_STATUS['status_code']] = self::HARD_BOUNCE_STATUS['status_name'];
            $status['detail'] = 'This is a confirmed status of the HardBounce.';
        }

        if($this->Domain->matchIsRealSoftComplaint($topicMatchMsg)) {
            $status['status'][self::SOFT_COMPLAINT_STATUS['status_code']] = self::SOFT_COMPLAINT_STATUS['status_name'];
            $status['detail'] = 'This is a confirmed status of the SoftComplaint.';
        }

        if($this->Domain->matchIsRealHardComplaint($topicMatchMsg)) {
            $status['status'][self::HARD_COMPLAINT_STATUS['status_code']] = self::HARD_COMPLAINT_STATUS['status_name'];
            $status['detail'] = 'This is a confirmed status of the HardComplaint.';
        }

        if($this->Domain->matchIsRealDelivery($topicMatchMsg)) {
            $status['status'][self::DELIVERY_STATUS['status_code']] = self::DELIVERY_STATUS['status_name'];
            $status['detail'] = 'This is a confirmed status of the Success.';
        }

        if(count($status['status']) > 1) {
            $status['detail'] = 'Matching states are duplicated. In conclusion, it becomes Undefined.';
        }

        return $status;
    }
}
