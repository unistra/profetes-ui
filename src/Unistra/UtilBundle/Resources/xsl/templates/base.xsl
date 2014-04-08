<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="html" indent="no" omit-xml-declaration="yes" />

    <xsl:template match="/">
        <xsl:text disable-output-escaping="yes">&lt;!DOCTYPE html&gt;</xsl:text>
        <xsl:apply-templates/>
    </xsl:template>

    <xsl:template match="html/head/title">
        <title>Université de Strasbourg : {% block title %}l'offre de formation{% endblock %}</title>
    </xsl:template>

    <xsl:template match="html/head/meta[@name = 'keywords']">{% block meta_keywords %}{% endblock %}</xsl:template>
    <xsl:template match="html/head/meta[@name = 'description']">{% block meta_description %}{% endblock %}</xsl:template>
    
    <xsl:template match="head/link/@href[starts-with(., 'index')]">
        <xsl:attribute name="href">/<xsl:value-of select="."/></xsl:attribute>
    </xsl:template>

    <xsl:template match="div[@id = 'breadcrumb']/ul[@class = 'breadcrumb']">
        <ul class="breadcrumb">
            <li>Nos formations<span class="divider">›</span></li>
            <li>Découvrir nos formations<span class="divider">›</span></li>
            <li class="active"><xsl:text>{% block breadcrumb %}{% endblock %}</xsl:text></li>
        </ul>
    </xsl:template>

    <xsl:template match="h1[@id = 'page-title']"><h1 id="page-title">{{ block('title') }}</h1></xsl:template>

    <xsl:template match="div[@id = 'main-content']/div[@class = 'span9']/div[@class = 'chapo']"/>

    <xsl:template match="div[@id = 'main-content']/div[@class = 'span9']/div[@id = 'focus']"/>

    <xsl:template match="div[@id = 'main-content']/div[@class = 'span9']/div[@class = 'content-menu']">
        <xsl:text>{% block body %}{% endblock %}</xsl:text>
    </xsl:template>

    <xsl:template match="div[@id = 'sidebar']">
        <div id="sidebar" class="span3">
            <xsl:text>{% block menu %}{% endblock %}</xsl:text>
            <xsl:apply-templates/>
        </div>
    </xsl:template>
    
    <xsl:template match="div[@id = 'sidebar']/div[@class = 'csc-default' and (child::node() = div[@id = 'slider-actu'])]"/>
    
    <xsl:template match="div[@id = 'slider-event']"/>

    <xsl:template match="html">
        <html>
            <xsl:attribute name="lang"><xsl:value-of select="@lang"/></xsl:attribute>
            <xsl:apply-templates/>
        </html>
    </xsl:template>

    <xsl:template match="head">
        <head>
            <xsl:apply-templates select="@* | node()"/>
            <xsl:text>{% block stylesheets %}{% endblock %}</xsl:text>
        </head>
    </xsl:template>

    <xsl:template match="body">
        <body>
            <xsl:apply-templates/>
            <xsl:text>{% block javascripts %}{% endblock %}</xsl:text>
            <!--<xsl:text>{% include('UnistraProfetesBundle:Default:googleAnalytics.html.twig') %}</xsl:text>-->
        </body>
    </xsl:template>

    <!-- On supprime les éléments suivants -->
    <xsl:template match="comment()" priority="2"/>
    <xsl:template match="meta[@name = 'generator'] | meta[@name = 'date']"/>
    <xsl:template match="script[contains(@src, 'anchor.js')]"/>

    <!-- Par défaut on recopie tout tel quel -->
    <xsl:template match="@* | node()">
        <xsl:copy>
            <xsl:apply-templates select="@* | node()"/>
        </xsl:copy>
    </xsl:template>

</xsl:stylesheet>
