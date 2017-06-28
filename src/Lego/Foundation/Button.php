<?php namespace Lego\Foundation;

use Collective\Html\HtmlFacade;
use Lego\Register\HighPriorityResponse;

/**
 * Html button or link.
 */
class Button
{
    /**
     * button link
     */
    private $url;

    /**
     * button text
     */
    private $text;

    /**
     * button id
     */
    private $id;

    private $attributes = [];

    public function __construct($text, $url = null, $id = null)
    {
        $this->text = $text;
        $this->id = $id ?: md5('_lego_button ' . $text);

        if ($url instanceof \Closure) {
            $this->action($url);
        } else {
            $this->url($url);
        }
    }

    public function text($text)
    {
        $this->text = $text;
        return $this;
    }

    public function url($url)
    {
        $this->url = $url;
        return $this;
    }

    public function id($id)
    {
        $this->id = $id;
    }

    /**
     * set html attribute
     *
     * @param $name
     * @param $value
     * @param bool $merge if true, new `value` will be merge to current `value`
     * @return $this
     */
    public function attribute($name, $value, $merge = true)
    {
        if (!$merge) {
            $this->attributes[$name] = $value;
            return $this;
        }

        $current = array_get($this->attributes, $name);
        if (is_array($current) && is_array($value)) {
            $current = array_unique(array_merge($current, $value));
        } elseif (is_string($current) && is_string($value)) {
            $current = trim($current) . ' ' . trim($value);
        } else {
            $current = $value;
        }
        $this->attributes[$name] = $current;
        return $this;
    }

    public function _class( $class)
    {
        return $this->attribute('_class', is_array($class) ? $class : explode(' ', trim($class)));
    }

    public function removeClass($class)
    {
        $all = array_get($this->attributes, '_class', []);
        if (!$all) {
            return;
        }

        $idx = array_search($class, $all);
        if ($idx !== false) {
            unset($this->attributes['_class'][$idx]);
        }
    }

    public function openInNewTab()
    {
        return $this->attribute('target', '_blank', false);
    }

    public function action(\Closure $action)
    {
        $resp = lego_register(HighPriorityResponse::class, $action, md5('button ' . $this->text));
        return $this->url($resp->url());
    }

    /** BootStrap Helpers */

    public function bootstrapStyle($style)
    {
        $styles = ['_default', 'primary', 'info', 'warning', 'danger'];
        foreach ($styles as $sty) {
            $this->removeClass('btn-' . $sty);
        }

        return $this->_class(['btn', 'btn-' . $style]);
    }

    /**
     * @param null|string $size if null, clear btn-*size* styles
     * @return Button
     */
    public function bootstrapSize($size = null)
    {
        $styles = ['sm', 'xs', 'lg'];
        foreach ($styles as $style) {
            $this->removeClass('btn-' . $style);
        }

        return is_null($size) ? $this : $this->_class('btn-' . $size);
    }

    function __toString()
    {
        $this->attribute('id', $this->id);
        $attributes = array_map(
            function ($value) {
                return is_array($value) ? join(' ', $value) : $value;
            },
            $this->attributes
        );

        /** @var \Illuminate\Support\HtmlString $html */
        $html = $this->url
            ? link_to($this->url, $this->text, $attributes)
            : HtmlFacade::tag('button', $this->text, $attributes);

        return $html->toHtml();
    }
}
