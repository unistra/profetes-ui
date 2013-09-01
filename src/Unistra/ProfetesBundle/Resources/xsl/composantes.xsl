<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output encoding="UTF-8" method="html" omit-xml-declaration="yes" indent="yes" />

    <xsl:param name="path"/>

    <xsl:template match="/">
        <ul>
            <xsl:apply-templates select="/composantes/composante"/>
        </ul>
    </xsl:template>

    <xsl:template match="composantes/composante">
        <xsl:if test="not(@cache)">
            <li><a href="{$path}{@id}"><xsl:value-of select="."/></a></li>
        </xsl:if>
    </xsl:template>
</xsl:stylesheet>
