<?php

/* core/themes/stable/templates/layout/maintenance-page.html.twig */
class __TwigTemplate_0ac9193aed08dfe8e64dfed93b5d04d1d2bc08e6993cd73ec5c4fbd54e998d57 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $tags = array("if" => 13);
        $filters = array("t" => 14);
        $functions = array();

        try {
            $this->env->getExtension('sandbox')->checkSecurity(
                array('if'),
                array('t'),
                array()
            );
        } catch (Twig_Sandbox_SecurityError $e) {
            $e->setTemplateFile($this->getTemplateName());

            if ($e instanceof Twig_Sandbox_SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

        // line 12
        echo "<header role=\"banner\">
  ";
        // line 13
        if ((isset($context["logo"]) ? $context["logo"] : null)) {
            // line 14
            echo "    <a href=\"";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["front_page"]) ? $context["front_page"] : null), "html", null, true));
            echo "\" title=\"";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar(t("Home")));
            echo "\" rel=\"home\">
      <img src=\"";
            // line 15
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["logo"]) ? $context["logo"] : null), "html", null, true));
            echo "\" alt=\"";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar(t("Home")));
            echo "\"/>
    </a>
  ";
        }
        // line 18
        echo "
  ";
        // line 19
        if (((isset($context["site_name"]) ? $context["site_name"] : null) || (isset($context["site_slogan"]) ? $context["site_slogan"] : null))) {
            // line 20
            echo "    ";
            if ((isset($context["site_name"]) ? $context["site_name"] : null)) {
                // line 21
                echo "      <h1>
        <a href=\"";
                // line 22
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["front_page"]) ? $context["front_page"] : null), "html", null, true));
                echo "\" title=\"";
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar(t("Home")));
                echo "\" rel=\"home\">";
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["site_name"]) ? $context["site_name"] : null), "html", null, true));
                echo "</a>
      </h1>
    ";
            }
            // line 25
            echo "
    ";
            // line 26
            if ((isset($context["site_slogan"]) ? $context["site_slogan"] : null)) {
                // line 27
                echo "      <div>";
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["site_slogan"]) ? $context["site_slogan"] : null), "html", null, true));
                echo "</div>
    ";
            }
            // line 29
            echo "  ";
        }
        // line 30
        echo "</header>

<main role=\"main\">
  ";
        // line 33
        if ((isset($context["title"]) ? $context["title"] : null)) {
            // line 34
            echo "    <h1>";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true));
            echo "</h1>
  ";
        }
        // line 36
        echo "
  ";
        // line 37
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "highlighted", array()), "html", null, true));
        echo "

  ";
        // line 39
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "content", array()), "html", null, true));
        echo "
</main>

";
        // line 42
        if ($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "sidebar_first", array())) {
            // line 43
            echo "  <aside role=\"complementary\">
    ";
            // line 44
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "sidebar_first", array()), "html", null, true));
            echo "
  </aside>
";
        }
        // line 47
        echo "
";
        // line 48
        if ($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "sidebar_second", array())) {
            // line 49
            echo "  <aside role=\"complementary\">
    ";
            // line 50
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "sidebar_second", array()), "html", null, true));
            echo "
  </aside>
";
        }
        // line 53
        echo "
";
        // line 54
        if ($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "footer", array())) {
            // line 55
            echo "  <footer role=\"contentinfo\">
    ";
            // line 56
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "footer", array()), "html", null, true));
            echo "
  </footer>
";
        }
    }

    public function getTemplateName()
    {
        return "core/themes/stable/templates/layout/maintenance-page.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  158 => 56,  155 => 55,  153 => 54,  150 => 53,  144 => 50,  141 => 49,  139 => 48,  136 => 47,  130 => 44,  127 => 43,  125 => 42,  119 => 39,  114 => 37,  111 => 36,  105 => 34,  103 => 33,  98 => 30,  95 => 29,  89 => 27,  87 => 26,  84 => 25,  74 => 22,  71 => 21,  68 => 20,  66 => 19,  63 => 18,  55 => 15,  48 => 14,  46 => 13,  43 => 12,);
    }
}
/* {#*/
/* /***/
/*  * @file*/
/*  * Theme override to display a single Drupal page while offline.*/
/*  **/
/*  * All available variables are mirrored in page.html.twig.*/
/*  * Some may be blank but they are provided for consistency.*/
/*  **/
/*  * @see template_preprocess_maintenance_page()*/
/*  *//* */
/* #}*/
/* <header role="banner">*/
/*   {% if logo %}*/
/*     <a href="{{ front_page }}" title="{{ 'Home'|t }}" rel="home">*/
/*       <img src="{{ logo }}" alt="{{ 'Home'|t }}"/>*/
/*     </a>*/
/*   {% endif %}*/
/* */
/*   {% if site_name or site_slogan %}*/
/*     {% if site_name %}*/
/*       <h1>*/
/*         <a href="{{ front_page }}" title="{{ 'Home'|t }}" rel="home">{{ site_name }}</a>*/
/*       </h1>*/
/*     {% endif %}*/
/* */
/*     {% if site_slogan %}*/
/*       <div>{{ site_slogan }}</div>*/
/*     {% endif %}*/
/*   {% endif %}*/
/* </header>*/
/* */
/* <main role="main">*/
/*   {% if title %}*/
/*     <h1>{{ title }}</h1>*/
/*   {% endif %}*/
/* */
/*   {{ page.highlighted }}*/
/* */
/*   {{ page.content }}*/
/* </main>*/
/* */
/* {% if page.sidebar_first %}*/
/*   <aside role="complementary">*/
/*     {{ page.sidebar_first }}*/
/*   </aside>*/
/* {% endif %}*/
/* */
/* {% if page.sidebar_second %}*/
/*   <aside role="complementary">*/
/*     {{ page.sidebar_second }}*/
/*   </aside>*/
/* {% endif %}*/
/* */
/* {% if page.footer %}*/
/*   <footer role="contentinfo">*/
/*     {{ page.footer }}*/
/*   </footer>*/
/* {% endif %}*/
/* */
