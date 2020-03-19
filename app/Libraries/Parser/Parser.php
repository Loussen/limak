<?php
namespace App\Libraries\Parser;

class Parser {
    private $url;
    private $domain;

    public function __construct(string $url, string $domain)
    {
        $this->url = $url;
        $this->domain = $domain;
    }
    public function parse()
    {
        return $this->domainfactory();
    }

    /**
     * @return DomainParser
     */
    private function domainfactory() {
        $dp = new DomainParser($this->url);
        switch ($this->domain) {
            case 'hepsiburada.com':
                return $dp->extractHepsiburada();
            break;
            case 'trendyol.com':
                return $dp->extractTrendYol();
            break;
            case 'flo.com.tr':
                return $dp->extractFLO();
            break;
            case 'uspoloassn.com':
                return $dp->extractPOLO();
            break;
            case 'lcwaikiki.com':
                return $dp->extractWaikiki();
            break;
            case 'dsdamat.com':
            case 'damattween.com':
                return $dp->extractDamat();
            break;
            case 'koton.com':
                return $dp->extractKoton();
            break;
            case 'colins.com.tr':
                return $dp->extractColins();
            break;
            case 'patirti.com':
                return $dp->extractPatirti();
            break;
            case 'morhipo.com':
                return $dp->extractMorhipo();
            break;
            case 'network.com.tr':
                return $dp->extractNetwork();
            break;
            case 'cacharel.com.tr':
                return $dp->extractCacharel();
            break;
            case 'kigili.com':
                return $dp->extractKigili();
            break;
            case 'gittigidiyor.com':
                return $dp->extractGidiyor();
            break;
            case 'n11.com':
                return $dp->extractn11();
            break;
            case 'defacto.com.tr':
                return $dp->extractDefacto();
            break;
            case 'mango.com':
                return $dp->extractMango();
            break;
        }
    }

}