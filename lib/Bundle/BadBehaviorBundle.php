<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

namespace Secondtruth\Gatekeeper\Bundle;

use Secondtruth\Gatekeeper\ACL\UserAgentACL;
use Secondtruth\Gatekeeper\Check\CookiesCheck;
use Secondtruth\Gatekeeper\Check\HeadersCheck;
use Secondtruth\Gatekeeper\Check\PostRequestCheck;
use Secondtruth\Gatekeeper\Check\ProtocolCheck;
use Secondtruth\Gatekeeper\Check\UrlCheck;
use Secondtruth\Gatekeeper\Check\UserAgentCheck;
use Secondtruth\Gatekeeper\Listing\StringList;

/**
 * Check for known spambots and block them.
 *
 * @author Michael Hampton <bad.bots@ioerror.us>
 * @author Christian Neff <christian.neff@gmail.com>
 */
class BadBehaviorBundle extends AbstractBundle
{
    /**
     * The settings
     *
     * @var array
     */
    protected $settings = [];

    /**
     * Creates a BadBehaviorBundle object.
     *
     * @param array $settings The settings
     */
    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }

    /**
     * {@inheritdoc}
     */
    protected function createChecks(): array
    {
        return [
            new UrlCheck(),

            // Check for abnormalities in the request
            new ProtocolCheck($this->settings),
            new CookiesCheck(),
            new HeadersCheck($this->settings),

            new UserAgentCheck(),

            // More intensive screening applies to POST requests
            new PostRequestCheck($this->settings)
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function createACLs(): array
    {
        $uaDenyList = new StringList();
        $uaDenyList->startsWith([
            '8484 Boston Project', // video poker/porn spam
            'adwords', // referrer spam
            'autoemailspider', // spam harvester
            'blogsearchbot-martin', // from honeypot
            'BrowserEmulator/', // open proxy software
            'CherryPicker', // spam harvester
            'core-project/', // FrontPage extension exploits
            'Diamond', // delivers spyware/adware
            'Digger', // spam harvester
            'ecollector', // spam harvester
            'EmailCollector', // spam harvester
            'Email Siphon', // spam harvester
            'EmailSiphon', // spam harvester
            'Forum Poster', // forum spambot
            'grub crawler', // misc comment/email spam
            'HttpProxy', // misc comment/email spam
            'Internet Explorer', // XMLRPC exploits seen
            'ISC Systems iRc', // spam harvester
            'Jakarta Commons', // customised spambots
            'Java 1.', // unidentified robots
            'Java/1.', // unidentified robots
            'libwww-perl', // unidentified robots
            'LWP', // unidentified robots
            'lwp', // unidentified robots
            'Microsoft Internet Explorer/', // too old; assumed robot
            'Microsoft URL', // unidentified robots
            'Missigua', // spam harvester
            'MJ12bot/v1.0.8', // malicious botnet
            'Morfeus', // vulnerability scanner
            'Movable Type', // customised spambots
            'Mozilla/0', // malicious software
            'Mozilla/1', // malicious software
            'Mozilla/2', // malicious software
            'Mozilla/3', // malicious software
            'Mozilla/4.0(', // from honeypot
            'Mozilla/4.0+(compatible;+', // suspicious harvester
            'Mozilla/4.0 (Hydra)', // brute force tool
            'MSIE', // malicious software
            'MVAClient', // automated hacking attempts
            'Nessus', // vulnerability scanner
            'NutchCVS', // unidentified robots
            'Nutscrape/', // misc comment spam
            'OmniExplorer', // spam harvester
            'Opera/9.64(', // comment spam bot
            'PMAFind', // vulnerability scanner
            'psycheclone', // spam harvester
            'PussyCat ', // misc comment spam
            'PycURL', // misc comment spam
            'Python-urllib', // commonly abused
            'revolt', // vulnerability scanner
            'sqlmap/', // SQL injection
            'Super Happy Fun ', // spam harvester
            'TrackBack/', // trackback spam
            'user', // suspicious harvester
            'User Agent: ', // spam harvester
            'User-Agent: ', // spam harvester
            'w3af', // vulnerability scanner
            'WebSite-X Suite', // misc comment spam
            'Winnie Poh', // Automated Coppermine hacks
            'Wordpress', // malicious software
            '"', // malicious software
        ]);
        $uaDenyList->contains([
            "\r", // A really dumb bot
            '<sc', // XSS exploit attempts
            '; Widows ', // misc comment/email spam
            'a href=', // referrer spam
            'compatible ; MSIE', // misc comment/email spam
            'compatible-', // misc comment/email spam
            'DTS Agent', // misc comment/email spam
            'Email Extractor', // spam harvester
            'Firebird/', // too old; assumed robot
            'Gecko/2525', // revisit this in 500 years
            'grub-client', // search engine ignores robots.txt
            'hanzoweb', // very badly behaved crawler
            'Havij', // SQL injection tool
            'Indy Library', // misc comment/email spam
            'Ming Mong', // brute force tool
            'MSIE 7.0;  Windows NT 5.2', // Cyveillance
            'Murzillo compatible', // comment spam bot
            '.NET CLR 1)', // free poker, etc.
            '.NET CLR1', // spam harvester
            'Netsparker', // vulnerability scanner
            'Nikto/', // vulnerability scanner
            'Perman Surfer', // old and very broken harvester
            'POE-Component-Client', // free poker, etc.
            'Teh Forest Lobster', // brute force tool
            'Turing Machine', // www.anonymizer.com abuse
            'Ubuntu/9.25', // comment spam bot
            'unspecified.mail', // stealth harvesters
            'User-agent: ', // spam harvester/splogger
            'WebaltBot', // spam harvester
            'WISEbot', // spam harvester
            'WISEnutbot', // spam harvester
            'Win95', // too old; assumed robot
            'Win98', // too old; assumed robot
            'WinME', // too old; assumed robot
            'Win 9x 4.90', // too old; assumed robot
            'Windows 3', // too old; assumed robot
            'Windows 95', // too old; assumed robot
            'Windows 98', // too old; assumed robot
            'Windows NT 4', // too old; assumed robot
            'Windows NT;', // too old; assumed robot
            'Windows NT 5.0;)', // wikispam bot
            'Windows NT 5.1;)', // wikispam bot
            'Windows XP 5', // spam harvester
            'WordPress/4.01', // pingback spam
            'Xedant Human Emulator',// spammer script engine
            'ZmEu', // exploit scanner
            '\\\\)', // spam harvester
        ]);
        $uaDenyList->matching([
            '/^[A-Z]{10}$/', // misc email spam
            '/[bcdfghjklmnpqrstvwxz ]{8,}/',
            '/MSIE [2345]/', // too old; assumed robot
        ]);

        return [
            new UserAgentACL(null, $uaDenyList)
        ];
    }
}
