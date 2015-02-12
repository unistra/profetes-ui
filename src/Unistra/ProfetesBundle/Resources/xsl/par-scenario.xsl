<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">
    <!--
        Copyright Université de Strasbourg (2015)

        Daniel Bessey <daniel.bessey@unistra.fr>

        This software is a computer program whose purpose is to display course information
        extracted from a Profetes database on a website.

        See LICENSE for more details.
    -->

    <xsl:output indent="yes" method="html" omit-xml-declaration="yes" />
    <xsl:param name="path"/>

    <xsl:template match="/">
        <xsl:if test="count(/formations/formation)">
            <ul class="liste-diplomes">
                <xsl:for-each select="/formations/formation">
                    <li>
                        <xsl:attribute name="class"><xsl:value-of select="@ead"/></xsl:attribute>
                        <a href="{$path}{@id}"><xsl:value-of select="."/></a>
                    </li>
                </xsl:for-each>
            </ul>
        </xsl:if>
    </xsl:template>

</xsl:stylesheet>