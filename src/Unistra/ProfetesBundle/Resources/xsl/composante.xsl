<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">
    
    <xsl:output encoding="UTF-8" method="html" omit-xml-declaration="yes" indent="yes" />
    
    <xsl:param name="path"/>
    
    <xsl:template match="/">
        <div id="content-header">
            <div id="breadcrumb">
                <ul class="breadcrumb">
                    <li><a href="#">Études et insertion</a> <span class="divider"> › </span></li>
                    <li><a href="#">Nos formations</a> <span class="divider"> › </span></li>
                    <li><a href="#">Diplômes</a> <span class="divider"> › </span></li>
                    <li class="active"><xsl:value-of select="/types-diplome/composante"/></li>
                </ul>
            </div>
            <h1 id="page-title"><xsl:value-of select="/types-diplome/composante"/></h1>
        </div>
        
        <div id="main-content" class="row-fluid">
            <xsl:apply-templates select="/types-diplome/type-diplome"/>
        </div>
    </xsl:template>
    
    <xsl:template match="types-diplome/type-diplome">
        <h2><xsl:value-of select="@name"/></h2>
        <ul>
            <xsl:for-each select="formation">
                <li><a href="{$path}{id}"><xsl:value-of select="nom"/></a></li>
            </xsl:for-each>
        </ul>
    </xsl:template>
    
</xsl:stylesheet>