<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* index.html */
class __TwigTemplate_618f6df8bd29f3fff6ab96547bacfeb41e91c018af873200fafd36a74d0e83df extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<!DOCTYPE html>
<html>
<head>
\t<title>Nyaf</title>
</head>
<body>
\t";
        // line 7
        echo twig_escape_filter($this->env, ($context["base_url"] ?? null), "html", null, true);
        echo "
\t<br>
\t";
        // line 9
        echo twig_escape_filter($this->env, ($context["page_active"] ?? null), "html", null, true);
        echo "
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "index.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  50 => 9,  45 => 7,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "index.html", "/www/wwwroot/yaf.bytewave.cn/application/views/index.html");
    }
}
