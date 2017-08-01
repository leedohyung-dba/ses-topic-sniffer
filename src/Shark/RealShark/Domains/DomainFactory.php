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
        'yahoo.co.jp' => 'YahooDomain'
    ];

    /**
     * myDomain
     *
     * @param string $domainName
     * @return class
     * @author lee <lee@fusic.co.jp>
     */
    public function myDomain($domainName) {
        $domainClass = $this->domainNamespace.$this->domainsClasses[$domainName];
        return new $domainClass();
    }
}
