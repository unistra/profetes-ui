<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <!--
        Copyright Université de Strasbourg (2015)

        Daniel Bessey <daniel.bessey@unistra.fr>

        This software is a computer program whose purpose is to display course information
        extracted from a Profetes database on a website.

        See LICENSE for more details.
    -->
    <xsl:template match="/">
        <p><xsl:value-of select="/a/b" /></p>
    </xsl:template>

</xsl:stylesheet>