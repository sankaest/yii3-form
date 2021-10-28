<?php

declare(strict_types=1);

namespace Yiisoft\Form\Widget;

use InvalidArgumentException;
use Stringable;
use Yiisoft\Form\Helper\HtmlForm;
use Yiisoft\Form\Widget\Attribute\CommonAttributes;
use Yiisoft\Form\Widget\Attribute\ModelAttributes;
use Yiisoft\Html\Tag\Input\Checkbox as CheckboxTag;
use Yiisoft\Widget\Widget;

/**
 * The input element with a type attribute whose value is "checkbox" represents a state or option that can be toggled.
 *
 * This method will generate the "checked" tag attribute according to the form attribute value.
 *
 * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.checkbox.html#input.checkbox
 */
final class Checkbox extends Widget
{
    use CommonAttributes;
    use ModelAttributes;

    private bool $enclosedByLabel = true;
    private string $label = '';
    private array $labelAttributes = [];
    /** @var bool|float|int|string|Stringable|null */
    private $uncheckValue = '0';
    /** @var scalar|Stringable|null */
    private $value = '1';

    /**
     * If the widget should be enclosed by label.
     *
     * @param bool $value If the widget should be en closed by label.
     *
     * @return static
     */
    public function enclosedByLabel(bool $value = true): self
    {
        $new = clone $this;
        $new->enclosedByLabel = $value;
        return $new;
    }

    /**
     * Label displayed next to the checkbox.
     *
     * It will NOT be HTML-encoded, therefore you can pass in HTML code such as an image tag. If this is coming from
     * end users, you should {@see encode()} it to prevent XSS attacks.
     *
     * When this option is specified, the checkbox will be enclosed by a label tag.
     *
     * @param string $value
     *
     * @return static
     *
     * @link https://www.w3.org/TR/html52/sec-forms.html#the-label-element
     */
    public function label(string $value): self
    {
        $new = clone $this;
        $new->label = $value;
        return $new;
    }

    /**
     * HTML attributes for the label tag.
     *
     * Do not set this option unless you set the "label" attributes.
     *
     * @param array $attributes
     *
     * @return static
     *
     * {@see \Yiisoft\Html\Html::renderTagAttributes()} for details on how attributes are being rendered.
     */
    public function labelAttributes(array $attributes = []): self
    {
        $new = clone $this;
        $new->labelAttributes = $attributes;
        return $new;
    }

    /**
     * @param bool|float|int|string|Stringable|null $value Value that corresponds to "unchecked" state of the input.
     *
     * @return static
     */
    public function uncheckValue($value): self
    {
        $new = clone $this;
        $new->uncheckValue = is_bool($value) ? (int) $value : $value;
        return $new;
    }

    /**
     * The value of the radio button.
     *
     * @param scalar|Stringable|null $value
     *
     * @return static
     *
     * @link https://www.w3.org/TR/2012/WD-html-markup-20120329/input.checkbox.html#input.checkbox.attrs.value
     */
    public function value($value): self
    {
        $new = clone $this;
        $new->attributes['value'] = $value;
        return $new;
    }

    /**
     * @return string the generated checkbox tag.
     */
    protected function run(): string
    {
        $new = clone $this;

        $checkbox = CheckboxTag::tag();
        $value = HtmlForm::getAttributeValue($new->getFormModel(), $new->attribute);

        if (is_iterable($value) || is_object($value)) {
            throw new InvalidArgumentException('Checkbox widget value can not be an iterable or an object.');
        }

        /** @var scalar|Stringable|null */
        $valueDefault = array_key_exists('value', $new->attributes) ? $new->attributes['value'] : $new->value;
        $valueDefault = is_bool($valueDefault) ? (int) $valueDefault : $valueDefault;

        if ($new->enclosedByLabel === true) {
            $label = $new->label !== '' ?
                $new->label : HtmlForm::getAttributeLabel($new->getFormModel(), $new->attribute);
            $checkbox = $checkbox->label($label, $new->labelAttributes);
        }

        return $checkbox
            ->checked("$value" === "$valueDefault")
            ->attributes($new->attributes)
            ->id($new->getId())
            ->name(HtmlForm::getInputName($new->getFormModel(), $new->attribute))
            ->uncheckValue($new->uncheckValue)
            ->value($valueDefault)
            ->render();
    }
}
