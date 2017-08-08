<?php
namespace SesTopicSniffer\Shark\RealShark\Domains;

/**
 * DomainFactory
 */
class DomainFactory
{
    private $domainNamespace = '\SesTopicSniffer\Shark\RealShark\Domains\\';
    private $domainsClasses = [
        'docomo.ne.jp' => 'DocomoDomain',
        'yahoo.co.jp' => 'YahooDomain',
        'outlook.com' => 'OutlookDomain',
        'gmail.com' => 'GmailDomain',
        'softbank.ne.jp' => 'SoftbankDomain',
        'ezweb.ne.jp' => 'EzwebDomain'
    ];

    /**
     * myDomain
     *
     * @param string $domainName
     * @return class
     * @author lee <lee@fusic.co.jp>
     */
    public function myDomain($domainName) {
        if(empty($this->domainsClasses[$domainName])) {
            return null;
        }
        $domainClass = $this->domainNamespace.$this->domainsClasses[$domainName];
        return new $domainClass();
    }
}
