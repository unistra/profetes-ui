<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output encoding="UTF-8" method="html" omit-xml-declaration="yes" indent="yes" />

    <xsl:param name="path"/>

    <xsl:template match="/">
        <xsl:apply-templates select="/types-diplome/type-diplome"/>
    </xsl:template>

    <xsl:template match="types-diplome/type-diplome">
        <h3><xsl:value-of select="@name"/></h3>
        <xsl:if test="count(formation)">
            <ul>
                <xsl:for-each select="formation">
                    <li><a href="{$path}{id}"><xsl:value-of select="nom"/></a></li>
                </xsl:for-each>
            </ul>
        </xsl:if>
    </xsl:template>

</xsl:stylesheet>
