<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <!--
        Copyright Université de Strasbourg (2015)

        Daniel Bessey <daniel.bessey@unistra.fr>

        This software is a computer program whose purpose is to display course information
        extracted from a Profetes database on a website.

        See LICENSE for more details.
    -->

    <xsl:output encoding="UTF-8" indent="no" method="html" omit-xml-declaration="yes"/>
    <xsl:param name="path"/>

    <xsl:template match="/type-diplome">
        <xsl:if test="count(formation)">
            <ul>
                <xsl:for-each select="formation">
                    <li><a href="{$path}{@id}"><xsl:value-of select="."/></a></li>
                </xsl:for-each>
            </ul>
        </xsl:if>
    </xsl:template>
</xsl:stylesheet>
