# AWS SES의 토픽정보 처리를 서브하는 라이브러리

![2017-08-01 17 22 15](https://user-images.githubusercontent.com/11989096/28815949-067d5ec6-76de-11e7-92ef-1011735c6831.png)

# 설치

# 사용예

## use example
```php
use SesTopicSniffer\SesTopicSniffer;

$stsSniffer = new SesTopicSniffer($sesRequestData);
$stsSniffer->getTopicDetailStatus();
```

## example result
```
[
	'ses_standard_status' => [
		'status_code' => '421',
		'status_name' => 'HardBounce(General)',
		'detail' => 'Amazon SES received a general hard bounce and recommends that you remove the recipient's email address from your mailing list.'
	],
	'fect_topic_status' => [
		'status' => [
			(int) 0 => [
				'status_code' => '420',
				'status_name' => 'HardBounce'
			],
			(int) 1 => [
				'status_code' => '510',
				'status_name' => 'SoftComplaint'
			],
			(int) 2 => [
				'status_code' => '520',
				'status_name' => 'HardComplaint'
			]
		],
		'detail' => 'Matching states are duplicated. In conclusion, it becomes Undefined.'
	],
	'topic_msg_point_info' => [
		'notificationType' => 'Bounce',
		'bounceType' => 'Permanent',
		'bounceSubType' => 'General',
		'bouncedRecipientsStatus' => [
			(int) 0 => [
				'action' => 'failed',
				'status' => '5.3.0'
			]
		]
	]
]
```

# 주의사항

