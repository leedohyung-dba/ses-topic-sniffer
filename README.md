# AWS SES의 토픽정보 처리를 서브하는 라이브러리

![2017-08-01 17 22 15](https://user-images.githubusercontent.com/11989096/28815949-067d5ec6-76de-11e7-92ef-1011735c6831.png)

# 설치

# 사용예

## use example
```php
use SesTopicSniffer\SesTopicSniffer;

$this->SesTopicSniffer = SesTopicSniffer::getInstance($snsRequestData->Message);
if (empty($this->SesTopicSniffer)) {
    return;
}

if ($this->SesTopicSniffer->isNotFactOrEqualSesStd()) {
    $this->sesStdStatusCtlHandler();
} else {
    $this->realStatusCtlHandler();
}
```

```php
// get info the ses standard status
$this->SesTopicSniffer->sesStdStatus

// get info the real status
$this->SesTopicSniffer->realStatus
```

```php
// messageId
$this->SesTopicSniffer->mail->messageId

// email address
$this->SesTopicSniffer->mail->destination[0]
```

```php
SesTopicSniffer::UNDEFINED_BOUNCE_STATUS_CODE
SesTopicSniffer::SOFT_BOUNCE_STATUS_CODE
SesTopicSniffer::HARD_BOUNCE_STATUS_CODE

SesTopicSniffer::COMPLAINT_STATUS_CODE
SesTopicSniffer::SOFT_COMPLAINT_STATUS_CODE
SesTopicSniffer::HARD_COMPLAINT_STATUS_CODE

SesTopicSniffer::DELIVERY_STATUS_CODE
```

## example result
```
// $this->SesTopicSniffer->mail
(
    [timestamp] => 2017-08-08T12:07:11.000Z
    [source] => =?utf-8?B?c2lnZnk=?= <sender@sigfy.jp>
    [sourceArn] => arn:aws:ses:us-west-2:278359588002:identity/sigfy.jp
    [sourceIp] => 54.64.122.239
    [sendingAccountId] => 278359588002
    [messageId] => 0101015dc1bdd93f-f7b8d7ab-681d-4954-a8c2-7272673180cd-000000
    [destination] => Array
        (
            [0] => lee_hoge_yayaya@fusic.co.jp
        )

)
```

```
// $this->SesTopicSniffer->realStatus
(
    [status] => Array
        (
            [420] => HardBounce
            [510] => SoftComplaint
            [520] => HardComplaint
        )

    [detail] => Matching states are duplicated. In conclusion, it becomes Undefined.
)
```

```
// $this->SesTopicSniffer->sesStdStatus
(
    [status_code] => 420
    [status_name] => HardBounce
    [status_sub_code] => 421
    [status_sub_name] => General
    [detail] => Amazon SES received a general hard bounce and recommends that you remove the recipient's email address from your mailing list.
)
```

```
// $this->SesTopicSniffer->topicMsg
(
    [notificationType] => Bounce
    [bounceType] => Permanent
    [bounceSubType] => General
    [bouncedRecipientsStatus] => Array
        (
            [0] => Array
                (
                    [action] => failed
                    [status] => 5.1.1
                )

        )

)
```

# 주의사항

