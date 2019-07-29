<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ToolsetAdvancedExport\Twig\Extension;

use ToolsetAdvancedExport\Twig\NodeVisitor\EscaperNodeVisitor;
use ToolsetAdvancedExport\Twig\TokenParser\AutoEscapeTokenParser;
use ToolsetAdvancedExport\Twig\TwigFilter;
/**
 * @final
 */
class EscaperExtension extends \ToolsetAdvancedExport\Twig\Extension\AbstractExtension
{
    protected $defaultStrategy;
    /**
     * @param string|false|callable $defaultStrategy An escaping strategy
     *
     * @see setDefaultStrategy()
     */
    public function __construct($defaultStrategy = 'html')
    {
        $this->setDefaultStrategy($defaultStrategy);
    }
    public function getTokenParsers()
    {
        return [new \ToolsetAdvancedExport\Twig\TokenParser\AutoEscapeTokenParser()];
    }
    public function getNodeVisitors()
    {
        return [new \ToolsetAdvancedExport\Twig\NodeVisitor\EscaperNodeVisitor()];
    }
    public function getFilters()
    {
        return [new \ToolsetAdvancedExport\Twig\TwigFilter('raw', 'twig_raw_filter', ['is_safe' => ['all']])];
    }
    /**
     * Sets the default strategy to use when not defined by the user.
     *
     * The strategy can be a valid PHP callback that takes the template
     * name as an argument and returns the strategy to use.
     *
     * @param string|false|callable $defaultStrategy An escaping strategy
     */
    public function setDefaultStrategy($defaultStrategy)
    {
        // for BC
        if (\true === $defaultStrategy) {
            @\trigger_error('Using "true" as the default strategy is deprecated since version 1.21. Use "html" instead.', \E_USER_DEPRECATED);
            $defaultStrategy = 'html';
        }
        if ('filename' === $defaultStrategy) {
            @\trigger_error('Using "filename" as the default strategy is deprecated since version 1.27. Use "name" instead.', \E_USER_DEPRECATED);
            $defaultStrategy = 'name';
        }
        if ('name' === $defaultStrategy) {
            $defaultStrategy = ['ToolsetAdvancedExport\\Twig\\FileExtensionEscapingStrategy', 'guess'];
        }
        $this->defaultStrategy = $defaultStrategy;
    }
    /**
     * Gets the default strategy to use when not defined by the user.
     *
     * @param string $name The template name
     *
     * @return string|false The default strategy to use for the template
     */
    public function getDefaultStrategy($name)
    {
        // disable string callables to avoid calling a function named html or js,
        // or any other upcoming escaping strategy
        if (!\is_string($this->defaultStrategy) && \false !== $this->defaultStrategy) {
            return \call_user_func($this->defaultStrategy, $name);
        }
        return $this->defaultStrategy;
    }
    public function getName()
    {
        return 'escaper';
    }
}
\class_alias('ToolsetAdvancedExport\\Twig\\Extension\\EscaperExtension', 'ToolsetAdvancedExport\\Twig_Extension_Escaper');
namespace ToolsetAdvancedExport;

/**
 * Marks a variable as being safe.
 *
 * @param string $string A PHP variable
 *
 * @return string
 */
function twig_raw_filter($string)
{
    return $string;
}
