<?php
/**
 * @copyright Copyright (c) 2018 Ivan Orlov
 * @license   https://github.com/demisang/yii2-safe-text/blob/master/LICENSE
 * @link      https://github.com/demisang/yii2-safe-text#readme
 * @author    Ivan Orlov <gnasimed@gmail.com>
 */

namespace demi\safeText;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Widget for safe output text.
 *
 * Examples:
 *
 * Hide phone number
 * <?= \demi\safeText\Widget::widget(['text' => '+555 478 24 75']) ?>
 *
 * Hide email
 * ```php
 * <?= \demi\safeText\Widget::widget([
 *  'url' => 'skype:girlfriend?chat',
 *  'text' => 'girlfriend',
 *  'linkTextTemplate' => '<i class="fa fa-skype"></i> {text}',
 *  'linkOptions' => [
 *  'class' => 'skype-link',
 *  'target' => '_blank',
 *  ],
 * ]) ?>
 * ```
 *
 * @package demi\safeText
 */
class Widget extends \yii\base\Widget
{
    /**
     * Text for safing
     *
     * @var string
     */
    public $text;
    /**
     * Url for text if needeed. Link will also be safe
     *
     * @var array|string|false
     */
    public $url = false;
    /**
     * JavaScript attributes for <a> tag.
     * for example: ['class' => 'testClass']
     *
     * @var array
     */
    public $linkOptions = [];
    /**
     * Text template. You can insert any non-personal data to link text.
     * for example: '<i class="fa fa-envelope"></i> {text}'
     *
     * @var string
     */
    public $linkTextTemplate = '{text}';

    /**
     * @inheritdoc
     */
    public function run()
    {
        $output = '';
        // if text is empty
        if (empty($this->text)) {
            return $output;
        }

        $encoded = self::encode($this->text);
        $js_chars = Json::encode($encoded);

        if ($this->url === false || is_null($this->url)) {
            $script = "document.write($js_chars.reverse().join(''));";
        } else {
            $linkAttributes = '';

            foreach ($this->linkOptions as $k => $v) {
                $linkAttributes .= PHP_EOL . "    a.setAttribute('$k', '$v');";
            }

            $url_chars = Json::encode(self::encode(Url::to($this->url)));
            $script = "
(function() {
    var a = document.createElement('a');
    a.href = $url_chars.reverse().join('');$linkAttributes
    var text = $js_chars.reverse().join('');
    var template = '$this->linkTextTemplate';
    a.innerHTML = template.replace('{text}', text);

    document.write(a.outerHTML);
}());
";
        }
        $output .= Html::script($script) . PHP_EOL;

        return $output;
    }

    /**
     * Encode unsafe string
     *
     * @param string $text
     *
     * @return string
     */
    public static function encode($text)
    {
        // Splitting a string into individual characters
        $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);

        // Expand string in reverse order
        return array_reverse($chars);
    }

    /**
     * Decode safe string
     *
     * @param string $text
     *
     * @return string
     */
    public static function decode($text)
    {
        // Splitting a string into individual characters
        $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);

        // Expand string in reverse order
        return array_reverse($chars);
    }
}
