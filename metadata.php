<?php

declare(strict_types=1);

use OxidEsales\Eshop\Core\ViewConfig;
use OxidEsales\Eshop\Core\ViewHelper\JavaScriptRenderer;
use OxidProfessionalServices\Usercentrics\Core\ViewConfig as UsercentricsViewConfig;
use OxidProfessionalServices\Usercentrics\Core\ScriptRenderer;
use OxidProfessionalServices\Usercentrics\Service\Integration\Pattern;

$sMetadataVersion = '2.1';
$aModule = [
    'id' => 'oxps_usercentrics',
    'title' => 'OXID Cookie Management powered by usercentrics',
    'description' => [
        'de' => 'Die Usercentrics Consent Management Platform (CMP) ermöglicht Ihnen, Ihre Marketing- und Datenstrategie
                 mit rechtlichen Anforderungen in Einklang zu bringen.</p>
                 <h2>Registrieren Sie sich deshalb jetzt bei Usercentrics</h2>  
                 <form target="_top" method="GET" action="https://usercentrics.com/de/?partnerid=16967">               
                     <input type="submit" value="Jetzt registrieren">
                     <p>
                         Sollte jemand anderes in Ihrem Unternehmen die Bestellung durchführen geben Sie bitte zwingend die Oxid Partner Id 16967 bei der Bestellung an
                         um die Integration vollständig nutzen zu können.<br>
                         Zu diesem Zweck können Sie diesen Link weitergeben: https://usercentrics.com/de/?partnerid=16967        
                     </p>
                 </form>
                 ',
        'en' => 'The Usercentrics Consent Management Platform (CMP) enables you to harmonize your marketing and data 
                 strategy with legal requirements.</p>
                 <h2>Register now for Usercentrics</h2>  
                 <form target="_top" method="GET" action="https://usercentrics.com/?partnerid=16967">               
                     <input type="submit" value="Register Now">
                     <p>
                         In case someone else is doing the registration for you, they have to use the partner id 16967
                         during the registration.<br>
                         For that reason you can forward this link to them: https://usercentrics.com/?partnerid=16967
                     </p>
                 </form>
                 '
    ],
    'version' => '1.1.1',
    'author' => 'OXID Professional Services',
    'events' => [],

    'templates' => [],

    'blocks' => [
        [
            'template' => 'layout/base.tpl',
            'block' => 'base_js',
            'file' => 'src/views/blocks/base_js.tpl'
        ],
        [
            'template' => 'layout/base.tpl',
            'block' => 'head_meta_description',
            'file' => 'src/views/blocks/head_meta_description.tpl'
        ],
    ],

    'settings' => [
        [
            'group' => 'usercentrics_advanced',
            'name'  => 'smartDataProtectorActive',
            'type'  => 'bool',
            'value' => true
        ],
        [
            'group' => 'usercentrics_advanced',
            'name' => 'usercentricsId',
            'type' => 'str',
            'value' => ''
        ],
        [
            'group' => 'usercentrics_advanced',
            'name' => 'usercentricsMode',
            'type' => 'select',
            'value' => 'CMPv2',
            'constraints' =>
                Pattern\CmpV1::VERSION_NAME . '|' .
                Pattern\CmpV2::VERSION_NAME . '|' .
                Pattern\CmpV2Legacy::VERSION_NAME . '|' .
                Pattern\CmpV2Tcf::VERSION_NAME . '|' .
                Pattern\CmpV2TcfLegacy::VERSION_NAME . '|' .
                Pattern\Custom::VERSION_NAME
        ],
    ],

    'controllers' => [],

    'extend' => [
        JavaScriptRenderer::class => ScriptRenderer::class,
        ViewConfig::class => UsercentricsViewConfig::class
    ]
];
