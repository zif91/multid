<?php

namespace Commerce\Controllers\Traits;

trait CustomTemplatesPathTrait
{
    public function setCustomLanguage()
    {
        $customLang = $this->getCFGDef('customLang');

        if (!empty($customLang)) {
            $this->getCustomLang($customLang);
        }
    }

    public function initializeCustomTemplatesPath($cfg)
    {
        if (!isset($cfg['templatePath'])) {
            $templatePath = ci()->commerce->getSetting('templates_path');
            $templatePath = isset($templatePath) ? trim($templatePath, '/ ') : '';

            if (!empty($templatePath)) {
                $cfg['templatePath'] = $templatePath . '/';
            } else {
                $cfg['templatePath'] = 'assets/plugins/commerce/templates/front/';
            }

            if (!isset($cfg['templateExtension'])) {
                $cfg['templateExtension'] = 'tpl';
            }
        }

        return $cfg;
    }
}
