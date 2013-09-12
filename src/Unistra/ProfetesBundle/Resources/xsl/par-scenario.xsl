<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">
    
    <xsl:output indent="yes" method="html" omit-xml-declaration="yes" />
    <xsl:param name="path"/>
    
    <xsl:template match="/">
        <xsl:if test="count(/formations/formation)">
            <ul class="liste-diplomes">
                <xsl:for-each select="/formations/formation">
                    <li>
                        <a href="{$path}{@id}"><xsl:value-of select="."/></a>
                    </li>
                </xsl:for-each>
            </ul>
        </xsl:if>
    </xsl:template>
    
</xsl:stylesheet>