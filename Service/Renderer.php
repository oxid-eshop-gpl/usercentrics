<?php

declare(strict_types=1);

namespace OxidProfessionalServices\Usercentrics\Service;

use Exception;

final class Renderer implements RendererInterface
{
    /**
     * @var ScriptServiceMapperInterface
     */
    private $scriptServiceMapper;

    /**
     * Renderer constructor.
     * @param ScriptServiceMapperInterface $scriptServiceMapper
     */
    public function __construct(ScriptServiceMapperInterface $scriptServiceMapper)
    {
        $this->scriptServiceMapper = $scriptServiceMapper;
    }

    /**
     * @param array<int,array<string>> $pathGroups // [ 10 => ["test.js","test2.js"] ]
     */
    public function formFilesOutput(array $pathGroups, string $widget): string
    {
        if (!count($pathGroups)) {
            return '';
        }

        ksort($pathGroups); // Sort by priority.

        /** @var string[] $sources */
        $sources = [];
        foreach ($pathGroups as $priorityGroup) {
            /** @var string $onePath */
            foreach ($priorityGroup as $onePath) {
                if (!in_array($onePath, $sources)) {
                    $sources[] = (string)$onePath;
                }
            }
        }

        if ($widget) {
            throw new Exception("Widgets are not yet supported");
        }

        return $this->prepareScriptUrlsOutput($sources);
    }

    /**
     * @param array<string> $sources //[ "test.js","test2.js"]
     *
     * see https://usercentrics.com/knowledge-hub/direct-integration-usercentrics-script-website/#Assign_data_attributes
     */
    protected function prepareScriptUrlsOutput(array $sources): string
    {
        $outputs = [];

        foreach ($sources as $source) {
            $data = '';
            $type = '';
            $src = ' src="' . $source . '"';

            $service = $this->scriptServiceMapper->getServiceByScriptPath($source);
            if ($service !== null) {
                $type = ' type="text/plain"';
                $data = ' data-usercentrics="' . $service->getName() . '"';
            }
            $outputs[] = "<script{$type}{$data}{$src}></script>";
        }

        return implode(PHP_EOL, $outputs);
    }

    public function encloseScriptSnippet(string $scriptsOutput, string $widget, bool $isAjaxRequest): string
    {
        if ($scriptsOutput) {
            if ($widget && !$isAjaxRequest) {
                throw new Exception("Widgets are not yet supported");
            }
            $id = $this->scriptServiceMapper->getIdForSnippet($scriptsOutput);
            $service = $this->scriptServiceMapper->getServiceBySnippet($id);
            $data = '';
            $type = '';
            if ($service !== null) {
                $type = ' type="text/plain"';
                $data = ' data-usercentrics="' . $service->getName() . '"';
            }
            $dataOxid = " data-oxid=\"$id\"";

            return "<script{$type}{$data}{$dataOxid}>$scriptsOutput</script>";
        }
        return "";
    }
}
