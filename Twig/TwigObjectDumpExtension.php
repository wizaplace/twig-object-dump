<?php

namespace Wizaplace\TwigObjectDumpBundle\Twig;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class TwigObjectDumpExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('d', [$this, 'objectDump'], ['is_safe' => ['html'], 'needs_environment' => true]),
        );
    }

    public function objectDump(\Twig_Environment $env, $value) : string
    {
        if (!$env->isDebug()) {
            return '';
        }

        // If it's not an object fall back on Symfony's VarDumper
        if (!is_object($value)) {
            $dump = fopen('php://memory', 'r+b');
            $dumper = new HtmlDumper($dump);
            $cloner = new VarCloner();
            $dumper->dump($cloner->cloneVar($value));
            rewind($dump);

            return stream_get_contents($dump);
        }

        $output = sprintf('%s<br>', get_class($value));
        $class = new \ReflectionObject($value);
        foreach ($class->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->isStatic() || $method->isConstructor()) {
                continue;
            }
            $output .= sprintf(
                '    <span class="twig-object-dump-key">%s()</span> %s<br>',
                $method->getName(),
                $method->getReturnType() ? ': ' . $method->getReturnType() : ''
            );
        }
        $output = sprintf('<pre class="twig-object-dump">%s</pre>', $output);

        // Inspired from Symfony's dump
        $style = <<<HTML
<style> pre.twig-object-dump { display: block; white-space: pre; padding: 5px; } pre.twig-object-dump span { display: inline; } pre.twig-object-dump{display: block; padding: 5px; background-color:#18171B; color:#FF8400; line-height:1.2em; font:12px Menlo, Monaco, Consolas, monospace; word-wrap: break-word; white-space: pre-wrap; position:relative; z-index:99999; word-break: normal}pre.twig-object-dump .twig-object-dump-key{color:#56DB3A}</style>
HTML;
        return $style . $output;
    }
}
