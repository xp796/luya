<?php

namespace cmsadmin\blocks;

use cmsadmin\Module;
use cms\helpers\TagParser;

class TextBlock extends \cmsadmin\base\Block
{
    public $module = 'cmsadmin';
    
    public $cacheEnabled = true;

    public function name()
    {
        return Module::t('block_text_name');
    }

    public function icon()
    {
        return 'format_align_left';
    }

    public function config()
    {
        return [
            'vars' => [
                ['var' => 'content', 'label' => Module::t('block_text_content_label'), 'type' => 'zaa-textarea'],
                ['var' => 'textType', 'label' => Module::t('block_text_texttype_label'), 'initvalue' => 0, 'type' => 'zaa-select', 'options' => [
                        ['value' => 0, 'label' => Module::t('block_text_texttype_normal')],
                        ['value' => 1, 'label' => Module::t('block_text_texttype_markdown')],
                    ],
                ],
            ],
        ];
    }

    public function getText()
    {
        $text = $this->getVarValue('content');

        if ($this->getVarValue('textType') == 1) {
            return TagParser::convertWithMarkdown($text);
        }

        return $text;
    }

    public function extraVars()
    {
        return [
            'text' => $this->getText(),
        ];
    }

    public function twigFrontend()
    {
        return '{% if vars.content is not empty and vars.textType == 1 %}{{ extras.text }}{% elseif vars.content is not empty and vars.textType == 0 %}<p>{{ extras.text|nl2br }}</p>{% endif %}';
    }

    public function twigAdmin()
    {
        return '<p>{% if vars.content is empty %}<span class="block__empty-text">' . Module::t('block_text_no_content') . '</span>'.
        '{% elseif vars.content is not empty and vars.textType == 1 %}{{ extras.text }}{% elseif vars.content is not empty %}{{ extras.text|nl2br }}{% endif %}</p>';
    }
}
