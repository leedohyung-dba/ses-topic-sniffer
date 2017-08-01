<?php
namespace SesTopicSniffer\Shark\RealShark;

use SesTopicSniffer\Shark\SharkContainer;
use SesTopicSniffer\Shark\RealShark\Domains\DomainFactory;

/**
 * TopicShark
 */
class TopicShark implements SharkContainer
{
    const BOUNCE_LIB_STATUS = [
        'status_code' => '400',
        'status_name' => 'Bounce'
    ];

    const SOFT_BOUNCE_LIB_STATUS = [
        'status_code' => '410',
        'status_name' => 'SoftBounce'
    ];

    const HARD_BOUNCE_LIB_STATUS = [
        'status_code' => '420',
        'status_name' => 'HardBounce'
    ];

    const COMPLAINT_LIB_STATUS = [
        'status_code' => '500',
        'status_name' => 'Complaint'
    ];

    const SOFT_COMPLAINT_LIB_STATUS = [
        'status_code' => '510',
        'status_name' => 'SoftComplaint'
    ];

    const HARD_COMPLAINT_LIB_STATUS = [
        'status_code' => '520',
        'status_name' => 'HardComplaint'
    ];

    const DELIVERY_LIB_STATUS = [
        'status_code' => '200',
        'status_name' => 'Delivery'
    ];

    const UNDEFINED_STATUS = [
        'status_code' => '0',
        'status_name' => 'Undefined'
    ];

    const RESULT_FORMAT = [
        'status' => [],
        'detail' => ''
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
            array_push($status['status'], self::UNDEFINED_STATUS);
            $status['detail'] = 'notting match of case to topic message';
            return $status;
        }

        if($this->Domain->matchIsRealSoftBounce($topicMatchMsg)) {
            array_push($status['status'], self::SOFT_BOUNCE_LIB_STATUS);
            $status['detail'] = 'This is a confirmed result of the SoftBounce.';
        }

        if($this->Domain->matchIsRealHardBounce($topicMatchMsg)) {
            array_push($status['status'], self::HARD_BOUNCE_LIB_STATUS);
            $status['detail'] = 'This is a confirmed result of the HardBounce.';
        }

        if($this->Domain->matchIsRealSoftComplaint($topicMatchMsg)) {
            array_push($status['status'], self::SOFT_COMPLAINT_LIB_STATUS);
            $status['detail'] = 'This is a confirmed result of the SoftComplaint.';
        }

        if($this->Domain->matchIsRealHardComplaint($topicMatchMsg)) {
            array_push($status['status'], self::HARD_COMPLAINT_LIB_STATUS);
            $status['detail'] = 'This is a confirmed result of the HardComplaint.';
        }

        if($this->Domain->matchIsRealDelivery($topicMatchMsg)) {
            array_push($status['status'], self::DELIVERY_LIB_STATUS);
            $status['detail'] = 'This is a confirmed result of the Success.';
        }

        if(empty($topicMatchMsg)) {
            array_push($status['status'], self::UNDEFINED_STATUS);
            $status['detail'] = 'notting match of case to topic message';
            return $status;
        }

        if(count($status['status']) > 1) {
            $status['detail'] = 'Matching states are duplicated. In conclusion, it becomes Undefined.';
        }

        return $status;
    }
}
