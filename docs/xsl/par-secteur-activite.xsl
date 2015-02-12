<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">
    
    <xsl:output encoding="UTF-8" omit-xml-declaration="yes" method="html" indent="yes" />
    
    <xsl:template match="/">
        <html>
            <head>
                <title>types de diplômes</title>
            </head>
            <body>
                <xsl:apply-templates select="secteurs-activite"/>
            </body>
        </html>
    </xsl:template>
    
    <xsl:template match="secteurs-activite">
        <h1>Formations par secteurs d'activité</h1>
        <ul>
            <xsl:for-each select="secteur-activite">
                <li><a href="http://www.unistra.fr/profetes-v2/secteur-activite/{.}"><xsl:value-of select="."/></a></li>
            </xsl:for-each>
        </ul>
    </xsl:template>
    
</xsl:stylesheet>