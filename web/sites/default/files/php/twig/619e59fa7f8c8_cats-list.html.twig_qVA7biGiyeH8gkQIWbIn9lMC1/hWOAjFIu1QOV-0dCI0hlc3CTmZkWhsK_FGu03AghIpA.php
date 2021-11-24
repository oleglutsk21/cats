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

/* modules/custom/oleg/templates/cats-list.html.twig */
class __TwigTemplate_9547c9c996351feb797291a2fde1a42a4b535f3323daf206e7cc8ade3d6479b3 extends \Twig\Template
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
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<div class=\"cats__form\">
";
        // line 2
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["form"] ?? null), 2, $this->source), "html", null, true);
        echo "
</div>

<div class=\"cats__items\">
    ";
        // line 6
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["row"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["items"]) {
            // line 7
            echo "        <div class=\"cats__item\">
            <h3 class=\"cat__name\">";
            // line 8
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["items"], "cat_name", [], "any", false, false, true, 8), 8, $this->source), "html", null, true);
            echo "</h3>
            <div class=\"cat__user-info\">
                <span class=\"cat__user-email\"><a class=\"user__email-link\" href=\"mailto:";
            // line 10
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["items"], "email", [], "any", false, false, true, 10), 10, $this->source), "html", null, true);
            echo "\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["items"], "email", [], "any", false, false, true, 10), 10, $this->source), "html", null, true);
            echo "</a></span>
                <span class=\"cat__created-on\">";
            // line 11
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["items"], "date", [], "any", false, false, true, 11), 11, $this->source), "d-m-Y H:i:s"), "html", null, true);
            echo "</span>
            </div>
            <div class=\"cat__photo\"><a class=\"cat__photo-link\" href=\"";
            // line 13
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["items"], "url", [], "any", false, false, true, 13), 13, $this->source), "html", null, true);
            echo "\" target=\"_blank\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["items"], "cat_photo", [], "any", false, false, true, 13), 13, $this->source), "html", null, true);
            echo "</a></div>
            ";
            // line 14
            if (twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "hasPermission", [0 => "administer nodes"], "method", false, false, true, 14)) {
                // line 15
                echo "                <div class=\"form-control__buttons\">
                    ";
                // line 16
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["items"], "delete", [], "any", false, false, true, 16), 16, $this->source), "html", null, true);
                echo "
                    <a class=\"btn-success btn\" >Edit</a>
                </div>
            ";
            }
            // line 20
            echo "        </div>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['items'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 22
        echo "</div>";
    }

    public function getTemplateName()
    {
        return "modules/custom/oleg/templates/cats-list.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  97 => 22,  90 => 20,  83 => 16,  80 => 15,  78 => 14,  72 => 13,  67 => 11,  61 => 10,  56 => 8,  53 => 7,  49 => 6,  42 => 2,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<div class=\"cats__form\">
{{ form }}
</div>

<div class=\"cats__items\">
    {% for items in row %}
        <div class=\"cats__item\">
            <h3 class=\"cat__name\">{{ items.cat_name }}</h3>
            <div class=\"cat__user-info\">
                <span class=\"cat__user-email\"><a class=\"user__email-link\" href=\"mailto:{{ items.email }}\">{{ items.email }}</a></span>
                <span class=\"cat__created-on\">{{ items.date |date('d-m-Y H:i:s') }}</span>
            </div>
            <div class=\"cat__photo\"><a class=\"cat__photo-link\" href=\"{{ items.url }}\" target=\"_blank\">{{ items.cat_photo }}</a></div>
            {% if user.hasPermission('administer nodes') %}
                <div class=\"form-control__buttons\">
                    {{ items.delete }}
                    <a class=\"btn-success btn\" >Edit</a>
                </div>
            {% endif %}
        </div>
    {% endfor %}
</div>", "modules/custom/oleg/templates/cats-list.html.twig", "/var/www/web/modules/custom/oleg/templates/cats-list.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("for" => 6, "if" => 14);
        static $filters = array("escape" => 2, "date" => 11);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['for', 'if'],
                ['escape', 'date'],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
