<?php

declare(strict_types=1);

namespace OxidProfessionalServices\Usercentrics\Service;

use OxidProfessionalServices\Usercentrics\DataObject\Service;
use OxidProfessionalServices\Usercentrics\Service\Configuration\ConfigurationDaoInterface;

final class ScriptServiceMapper implements ScriptServiceMapperInterface
{
    /**
     * @var ConfigurationDaoInterface
     */
    private $configurationDao;

    /**
     * This is a map of service path regex as key with a service id
     * @example: ['/some\/path\/to\/script.js$/S' => 'SomeConfiguredServiceId']
     *
     * @var array<string,?Service>
     */
    private $scriptPathToService;

    /**
     * @var array<string,?Service>
     */
    private $snippetToService;

    public function __construct(ConfigurationDaoInterface $configurationDao)
    {
        $this->configurationDao = $configurationDao;
        $this->scriptPathToService = $this->mapScriptPathsToServices();
        $this->snippetToService = $this->mapScriptSnippetToServices();
    }

    public function getServiceByScriptUrl(string $url): ?Service
    {
        foreach ($this->scriptPathToService as $pathRegEx => $service) {
            if (preg_match($pathRegEx, $url)) {
                return $service;
            }
        }
        return null;
    }

    public function getServiceBySnippetId(string $snippetId): ?Service
    {
        return $this->snippetToService[$snippetId] ?? null;
    }


    protected function getServiceById(string $serviceId): ?Service
    {
        $config = $this->configurationDao->getConfiguration();
        $services = $config->getServices();

        return $services[$serviceId] ?? null;
    }

    /**
     * @return array<string,?Service>
     */
    private function mapScriptPathsToServices(): array
    {
        $config = $this->configurationDao->getConfiguration();
        $scripts = $config->getScripts();
        $result = [];

        foreach ($scripts as $oneScript) {
            $serviceId = $oneScript->getServiceId();
            $path = $oneScript->getPath();
            //build a regex that will match if the URL's path ends with this path
            $regEx = '/' . preg_quote($path, '/') . '(:?\?|#|$)/S';
            $result[$regEx] = $this->getServiceById($serviceId);
        }

        return $result;
    }

    /**
     * @return array<string,?Service>
     */
    private function mapScriptSnippetToServices(): array
    {
        $config = $this->configurationDao->getConfiguration();
        $scripts = $config->getScriptSnippets();
        $result = [];

        foreach ($scripts as $oneScript) {
            $serviceId = $oneScript->getServiceId();
            $result[$oneScript->getId()] = $this->getServiceById($serviceId);
        }

        return $result;
    }

    public function calculateSnippetId(string $snippetContents): string
    {
        return md5(trim($snippetContents));
    }
}
