<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/">
        <p><xsl:value-of select="/a/b" /></p>
    </xsl:template>

</xsl:stylesheet>