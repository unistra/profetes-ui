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

    <xsl:output encoding="UTF-8" method="html" omit-xml-declaration="yes" indent="yes" />

    <xsl:param name="path"/>

    <xsl:template match="/">
        <div class="accordion" id="accordion2">
            <xsl:apply-templates select="/types-diplome/type-diplome"/>
        </div>
    </xsl:template>

    <xsl:template match="types-diplome/type-diplome">
        <xsl:if test="count(formation)">
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse{generate-id(.)}">
                        <h3><xsl:value-of select="@name"/></h3>
                    </a>
                </div>
                <div id="collapse{generate-id(.)}" class="accordion-body collapse">
                    <div class="accordion-inner">
                        <ul>
                            <xsl:for-each select="formation">
                                <li><a href="{$path}{id}"><xsl:value-of select="nom"/></a></li>
                            </xsl:for-each>
                        </ul>
                    </div>
                </div>
            </div>
        </xsl:if>
    </xsl:template>

</xsl:stylesheet>
