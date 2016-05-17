<?php

/* themes/ows_theme/templates/page--front.html.twig */
class __TwigTemplate_a8c58ac1fe04610e78db49bb765db98f5b072152d37e4d1784d398f6a6a7e91b extends Twig_Template
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
        $tags = array("if" => 76);
        $filters = array();
        $functions = array();

        try {
            $this->env->getExtension('sandbox')->checkSecurity(
                array('if'),
                array(),
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

        // line 67
        echo "
    <div class=\"layout-content\">
      ";
        // line 69
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "content", array()), "html", null, true));
        echo "

      <div class=\"menu-bar\">
        <a href=\"\" class=\"home\"><span>&nbsp;</span></a>
      </div>
    </div>";
        // line 75
        echo "
    ";
        // line 76
        if ($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "sidebar_first", array())) {
            // line 77
            echo "      <aside class=\"layout-sidebar-first\" role=\"complementary\">
        ";
            // line 78
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "sidebar_first", array()), "html", null, true));
            echo "
      </aside>
    ";
        }
        // line 81
        echo "
    ";
        // line 82
        if ($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "sidebar_second", array())) {
            // line 83
            echo "      <aside class=\"layout-sidebar-second\" role=\"complementary\">
        ";
            // line 84
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "sidebar_second", array()), "html", null, true));
            echo "
      </aside>
    ";
        }
        // line 87
        echo "
    ";
        // line 88
        if ($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "welcome_message", array())) {
            // line 89
            echo "      <div class=\"welcome-message\">";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "welcome_message", array()), "html", null, true));
            echo "</div>
    ";
        }
        // line 91
        echo "  </div>

  ";
        // line 93
        if ($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "footer", array())) {
            // line 94
            echo "    <footer role=\"contentinfo\">
      ";
            // line 95
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "footer", array()), "html", null, true));
            echo "
    </footer>
  ";
        }
        // line 98
        echo "
</div>";
    }

    public function getTemplateName()
    {
        return "themes/ows_theme/templates/page--front.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  109 => 98,  103 => 95,  100 => 94,  98 => 93,  94 => 91,  88 => 89,  86 => 88,  83 => 87,  77 => 84,  74 => 83,  72 => 82,  69 => 81,  63 => 78,  60 => 77,  58 => 76,  55 => 75,  47 => 69,  43 => 67,);
    }
}
/* {#*/
/* /***/
/*  * @file*/
/*  * Theme override to display a single page.*/
/*  **/
/*  * The doctype, html, head and body tags are not in this template. Instead they*/
/*  * can be found in the html.html.twig template in this directory.*/
/*  **/
/*  * Available variables:*/
/*  **/
/*  * General utility variables:*/
/*  * - base_path: The base URL path of the Drupal installation. Will usually be*/
/*  *   "/" unless you have installed Drupal in a sub-directory.*/
/*  * - is_front: A flag indicating if the current page is the front page.*/
/*  * - logged_in: A flag indicating if the user is registered and signed in.*/
/*  * - is_admin: A flag indicating if the user has permission to access*/
/*  *   administration pages.*/
/*  **/
/*  * Site identity:*/
/*  * - front_page: The URL of the front page. Use this instead of base_path when*/
/*  *   linking to the front page. This includes the language domain or prefix.*/
/*  **/
/*  * Page content (in order of occurrence in the default page.html.twig):*/
/*  * - node: Fully loaded node, if there is an automatically-loaded node*/
/*  *   associated with the page and the node ID is the second argument in the*/
/*  *   page's path (e.g. node/12345 and node/12345/revisions, but not*/
/*  *   comment/reply/12345).*/
/*  **/
/*  * Regions:*/
/*  * - page.header: Items for the header region.*/
/*  * - page.highlighted: Items for the highlighted region.*/
/*  * - page.primary_menu: Items for the primary menu region.*/
/*  * - page.secondary_menu: Items for the secondary menu region.*/
/*  * - page.highlighted: Items for the highlighted content region.*/
/*  * - page.help: Dynamic help text, mostly for admin pages.*/
/*  * - page.content: The main content of the current page.*/
/*  * - page.sidebar_first: Items for the first sidebar.*/
/*  * - page.sidebar_second: Items for the second sidebar.*/
/*  * - page.footer: Items for the footer region.*/
/*  * - page.breadcrumb: Items for the breadcrumb region.*/
/*  **/
/*  * @see template_preprocess_page()*/
/*  * @see html.html.twig*/
/*  *//* */
/* #*/
/* */
/* $settings['twig_debug'] = TRUE;*/
/* }*/
/* */
/* <div class="layout-container">*/
/*   <div class="load-container"><div class="loader">Loading...</div></div>*/
/*   <header role="banner">*/
/*     {{ page.header }}*/
/*   </header>*/
/* */
/*   {{ page.primary_menu }}*/
/*   {{ page.secondary_menu }}*/
/* */
/*   {{ page.breadcrumb }}*/
/* */
/*   {{ page.highlighted }}*/
/* */
/*   {{ page.help }}*/
/* */
/*   <div role="main">*/
/*     <a id="main-content" tabindex="-1"></a>{# link is in html.html.twig #}*/
/* */
/*     <div class="layout-content">*/
/*       {{ page.content }}*/
/* */
/*       <div class="menu-bar">*/
/*         <a href="" class="home"><span>&nbsp;</span></a>*/
/*       </div>*/
/*     </div>{# /.layout-content #}*/
/* */
/*     {% if page.sidebar_first %}*/
/*       <aside class="layout-sidebar-first" role="complementary">*/
/*         {{ page.sidebar_first }}*/
/*       </aside>*/
/*     {% endif %}*/
/* */
/*     {% if page.sidebar_second %}*/
/*       <aside class="layout-sidebar-second" role="complementary">*/
/*         {{ page.sidebar_second }}*/
/*       </aside>*/
/*     {% endif %}*/
/* */
/*     {% if page.welcome_message %}*/
/*       <div class="welcome-message">{{ page.welcome_message }}</div>*/
/*     {% endif %}*/
/*   </div>*/
/* */
/*   {% if page.footer %}*/
/*     <footer role="contentinfo">*/
/*       {{ page.footer }}*/
/*     </footer>*/
/*   {% endif %}*/
/* */
/* </div>{# /.layout-container #}*/
/* */
