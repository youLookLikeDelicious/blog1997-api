<?php
namespace App\Foundation;

use Parsedown;

use function HighlightUtilities\getStyleSheet;

class HighLightHtml
{
    /**
     * 高亮显示代码
     *
     * @param string $content
     * @param bool $isMarkdown 是否是markdown格式
     * @return string
     */
    public function make($content, $isMarkdown = false)
    {
        if ($isMarkdown) {
            $content = (new Parsedown())->text($content);
        }

        $hl = new \Highlight\Highlighter();
        $dom = new \DOMDocument();

        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES'));

        $codeElements = $dom->getElementsByTagName('code');
        if (!$codeElements->length) {
            $codeElements = $dom->getElementsByTagName('pre');
        }

        $styleMap = $this->getStyleMap();
        
        if (count($codeElements)) {
            foreach($codeElements as $element) {
                // 获取代码语言
                $lang = str_replace('language-', '', $element->getAttribute('class'));

                
                $element->setAttribute('style', $styleMap['hljs'] ?? '');

                if (!$lang) {
                    continue;
                }

                try{
                    $highlighted = $hl->highlight($lang, $element->nodeValue);
                    $element->nodeValue = '';
                    $fragment = $dom->createDocumentFragment();
                    $fragment->appendXML($highlighted->value);

                    $element->setAttribute('class', "hljs $highlighted->language");
                    $element->appendChild($fragment);

                    // 给每个 code标签中的 span元素添加行内样式
                    $spanEls = $element->getElementsByTagName('span');
                    foreach($spanEls as $span) {
                        $class = $span->getAttribute('class');
                        if (empty($styleMap[$class])) {
                            continue;
                        }
                        $span->setAttribute('style', $styleMap[$class]);
                    }
                } catch(\Exception $e) {
                    // 未识别的语言
                }
            }
        }

        // ul缩进处理
        $ulEls = $dom->getElementsByTagName('ul');

        foreach($ulEls as $el) {
            $el->setAttribute('style', 'margin-left: 20px');
        }

        $content = $dom->saveHTML($dom->getElementsByTagName('body')[0]);
        
        $content = mb_substr($content, 6, -7);

        // 给h标签添加样式
        $pattern = [ '/<h1/', '/<h2/', '/<h3/', '/<h4/'];
        $replacement = [
            '<h1 style="font-weight: bold; margin: 12px 0; font-size: 20px;"',
            '<h2 style="font-weight: bold; margin: 12px 0; font-size: 18px;"',
            '<h3 style="font-weight: bold; margin: 12px 0; font-size: 16px;"',
            '<h4 style="font-weight: bold; margin: 12px 0; font-size: 16px;"'
        ];

        $content = preg_replace($pattern, $replacement, $content);
        
        return $content;
    }

    /**
     * 将style样式转成行内样式
     *
     * @return array
     */
    protected function getStyleMap()
    {
        $styles = getStyleSheet('dracula');
        // 去掉注释和换行
        $styles = preg_replace('/[\n\r]/', '', $styles);
        $styles = preg_replace('/\/.*\//', '', $styles);
        preg_match_all('/(\.[^}]+?\})/', $styles, $matchedStyle);

        $map = [];

        foreach ($matchedStyle[1] as $style) {
            preg_match_all('/\.([a-zA-Z\-]+)/', $style, $classes);
            preg_match('/\{(.*?)\}/', $style, $content);
            $tempStack = array_combine($classes[1], array_fill(0, count($classes[1]), $content[1]));
            foreach($tempStack as $key => $v) {
                $map[$key] = ($map[$key] ?? '') . $v;
            }
        }

        $map['hljs'] = ($map['hljs'] ?? '') . 'font-size: 16px;';

        return $map;
    }
}