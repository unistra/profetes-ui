<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:cdm="http://cdm-fr.fr/2006/CDM-frSchema"
    version="1.0" exclude-result-prefixes="cdm">

    <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

    <xsl:template match="/">
        <div id="content-header">
            <div id="breadcrumb">
                <ul class="breadcrumb">
                    <li>Études et insertion <span class="divider"> › </span></li>
                    <li>Nos formations <span class="divider"> › </span></li>
                    <li class="active"><xsl:value-of select="/cdm:CDM/cdm:program/cdm:programName/cdm:text[@language = 'fr-FR']"/></li>
                </ul>
            </div>
            <h1 id="page-title"><xsl:value-of select="/cdm:CDM/cdm:program/cdm:programName/cdm:text[@language = 'fr-FR']"/></h1>
        </div>

        <div id="main-content" class="row-fluid">
            <xsl:apply-templates select="/cdm:CDM/cdm:program"/>
        </div>
    </xsl:template>

    <xsl:template match="/cdm:CDM/cdm:program">
        <div class="span9">
            <xsl:apply-templates select="cdm:programDescription/cdm:subBlock[@userDefined = 'DES']/cdm:subBlock[@userDefined = 'domaineMentionSpecialite']"/>
            <xsl:call-template name="menu-onglets"/>

            <div id="content-mapping">
                <xsl:apply-templates select="cdm:programDescription/cdm:subBlock[not(@userDefined = 'DES')]"/>
                <xsl:apply-templates select="cdm:learningObjectives/cdm:subBlock"/>
                <xsl:apply-templates select="cdm:formalPrerequisites/cdm:subBlock"/>
                <xsl:apply-templates select="cdm:qualification/cdm:studyQualification/cdm:subBlock"/>
                <xsl:apply-templates select="cdm:regulations/cdm:subBlock"/>
                <xsl:apply-templates select="cdm:qualification/cdm:profession/cdm:subBlock"/>
                <xsl:apply-templates select="cdm:infoBlock/cdm:subBlock[@userDefined = 'enSavoirPlus' and @blockLang = 'fr-FR']"/>
                <xsl:apply-templates select="cdm:infoBlock/cdm:subBlock[@userDefined = 'informationsComplementaires' and @blockLang = 'fr-FR']"/>
                <!--<xsl:apply-templates select="cdm:admissionInfo/cdm:admissionDescription/cdm:subBlock"/>-->
            </div>
        </div>

        <div id="sidebar" class="span3">
            <div class="bloc contacts">
                <h2 class="bloc-title"><i class="icon-perso-annuaire-bright"></i> Contacts</h2>
                <xsl:apply-templates select="/cdm:CDM/cdm:orgUnit/cdm:orgUnit[cdm:orgUnitKind/cdm:subBlock/cdm:subBlock = 'Composante']" />
                <xsl:for-each select="cdm:contacts/cdm:refPerson">
                    <xsl:variable name="idperson"><xsl:value-of select="@idRef"/></xsl:variable>
                    <xsl:apply-templates select="//cdm:person[@id = $idperson]"/>
                </xsl:for-each>
            </div>

            <xsl:apply-templates select="cdm:formOfTeaching"/>
        </div> 
    </xsl:template>


    <xsl:template match="/cdm:CDM/cdm:program/cdm:programDescription/cdm:subBlock[@userDefined = 'DES']/cdm:subBlock[@userDefined = 'domaineMentionSpecialite']">
        <div class="content-menu fiche-diplome">
            <ul class="content-menu-inner">
                <li><i class="icon-perso-puce-03"></i><strong>Domaine :</strong>&#160;<xsl:value-of select="cdm:subBlock[@userDefined = 'domaine' and @blockLang = 'fr-FR']"/></li>
                <xsl:if test="cdm:subBlock[@userDefined = 'mention' and @blockLang = 'fr-FR']">
                    <li><i class="icon-perso-puce-03"></i><strong>Mention :</strong>&#160;<xsl:value-of select="cdm:subBlock[@userDefined = 'mention' and @blockLang = 'fr-FR']"/></li>
                </xsl:if>
                <xsl:if test="cdm:subBlock[@userDefined = 'specialite' and @blockLang = 'fr-FR']">
                    <li><i class="icon-perso-puce-03"></i><strong>Spécialité :</strong>&#160;<xsl:value-of select="cdm:subBlock[@userDefined = 'specialite' and @blockLang = 'fr-FR']" /></li>
                </xsl:if>
            </ul>
        </div>
    </xsl:template>


    <xsl:template name="menu-onglets">
        <nav id="menu-onglets">
            <ul class="nav nav-tabs">
                <xsl:if test="/cdm:CDM/cdm:program/cdm:programDescription/cdm:subBlock[not(@userDefined = 'DES' and @blocLang = 'fr-FR')]">
                    <li class="active"><xsl:attribute name="id">profetes-<xsl:value-of select="generate-id(/cdm:CDM/cdm:program/cdm:programDescription/cdm:subBlock[not(@userDefined = 'DES') and @blockLang = 'fr-FR'])"/></xsl:attribute><a href="#profetes-bloc-{generate-id(/cdm:CDM/cdm:program/cdm:programDescription/cdm:subBlock[not(@userDefined = 'DES') and @blockLang = 'fr-FR'])}"><span>Présentation et objectifs</span></a></li>                    
                </xsl:if>

                <xsl:if test="/cdm:CDM/cdm:program/cdm:learningObjectives/cdm:subBlock">
                    <li><xsl:attribute name="id">profetes-<xsl:value-of select="generate-id(/cdm:CDM/cdm:program/cdm:learningObjectives/cdm:subBlock)"/></xsl:attribute><a href="#profetes-bloc-{generate-id(/cdm:CDM/cdm:program/cdm:learningObjectives/cdm:subBlock)}"><span>Savoir-faire et compétences</span></a></li>    
                </xsl:if>

                <xsl:if test="/cdm:CDM/cdm:program/cdm:formalPrerequisites/cdm:subBlock[@blockLang = 'fr-FR']">
                    <li><xsl:attribute name="id">profetes-<xsl:value-of select="generate-id(/cdm:CDM/cdm:program/cdm:formalPrerequisites/cdm:subBlock[@blockLang='fr-FR'])"/></xsl:attribute><a href="#profetes-bloc-{generate-id(/cdm:CDM/cdm:program/cdm:formalPrerequisites/cdm:subBlock[@blockLang='fr-FR'])}"><span>Conditions d'accès et pré-requis</span></a></li>    
                </xsl:if>

                <xsl:if test="/cdm:CDM/cdm:program/cdm:qualification/cdm:studyQualification/cdm:subBlock[@blockLang = 'fr-FR']">
                    <li><xsl:attribute name="id">profetes-<xsl:value-of select="generate-id(/cdm:CDM/cdm:program/cdm:qualification/cdm:studyQualification/cdm:subBlock[@blockLang = 'fr-FR'])"/></xsl:attribute><a href="#profetes-bloc-{generate-id(/cdm:CDM/cdm:program/cdm:qualification/cdm:studyQualification/cdm:subBlock[@blockLang = 'fr-FR'])}"><span>Poursuite d'études</span></a></li>    
                </xsl:if>

                <xsl:if test="/cdm:CDM/cdm:program/cdm:regulations/cdm:subBlock[@blockLang = 'fr-FR']">
                    <li><xsl:attribute name="id">profetes-<xsl:value-of select="generate-id(/cdm:CDM/cdm:program/cdm:regulations/cdm:subBlock[@blockLang = 'fr-FR'])"/></xsl:attribute><a href="#profetes-bloc-{generate-id(/cdm:CDM/cdm:program/cdm:regulations/cdm:subBlock[@blockLang = 'fr-FR'])}"><span>Contrôle des connaissances</span></a></li>    
                </xsl:if>

                <xsl:if test="/cdm:CDM/cdm:program/cdm:qualification/cdm:profession/cdm:subBlock[@blockLang = 'fr-FR']">
                    <li><xsl:attribute name="id">profetes-<xsl:value-of select="generate-id(/cdm:CDM/cdm:program/cdm:qualification/cdm:profession/cdm:subBlock[@blockLang='fr-FR'])"/></xsl:attribute><a href="#profetes-bloc-{generate-id(/cdm:CDM/cdm:program/cdm:qualification/cdm:profession/cdm:subBlock[@blockLang='fr-FR'])}"><span>Insertion professionnelle</span></a></li>
                </xsl:if>
            </ul>
        </nav>
    </xsl:template>


    <xsl:template match="cdm:programDescription/cdm:subBlock[not(@userDefined = 'DES') and @blockLang = 'fr-FR']" priority="100">
        <div class="profetes-bloc">
            <xsl:attribute name="id">profetes-bloc-<xsl:value-of select="generate-id(.)"/></xsl:attribute>
            <h2>Présentation et objectifs</h2>
            <xsl:apply-templates/>
        </div>
    </xsl:template>

    <xsl:template match="cdm:program/cdm:learningObjectives/cdm:subBlock[@blockLang = 'fr-FR']">
        <div class="profetes-bloc">
            <xsl:attribute name="id">profetes-bloc-<xsl:value-of select="generate-id(.)"/></xsl:attribute>
            <h2>Savoir-faire et compétences</h2>
            <xsl:apply-templates/>
        </div>
    </xsl:template>

    <xsl:template match="cdm:program/cdm:formalPrerequisites/cdm:subBlock[@blockLang = 'fr-FR']">
        <div class="profetes-bloc">
            <xsl:attribute name="id">profetes-bloc-<xsl:value-of select="generate-id(.)"/></xsl:attribute>
            <h2>Conditions d'accès et pré-requis</h2>
            <xsl:apply-templates/>
        </div>
    </xsl:template>

    <xsl:template match="cdm:program/cdm:qualification/cdm:studyQualification/cdm:subBlock[@blockLang = 'fr-FR']">
        <div class="profetes-bloc">
            <xsl:attribute name="id">profetes-bloc-<xsl:value-of select="generate-id(.)"/></xsl:attribute>
            <h2>Poursuite d'études</h2>
            <xsl:apply-templates/>
        </div>
    </xsl:template>

    <xsl:template match="cdm:program/cdm:regulations/cdm:subBlock[@blockLang = 'fr-FR']">
        <div class="profetes-bloc">
            <xsl:attribute name="id">profetes-bloc-<xsl:value-of select="generate-id(.)"/></xsl:attribute>
            <h2>Contrôle des connaissances</h2>
            <xsl:apply-templates/>
        </div>
    </xsl:template>

    <xsl:template match="cdm:program/cdm:qualification/cdm:profession/cdm:subBlock[@blockLang = 'fr-FR']">
        <div class="profetes-bloc">
            <xsl:attribute name="id">profetes-bloc-<xsl:value-of select="generate-id(.)"/></xsl:attribute>
            <h2>Insertion professionnelle</h2>
            <xsl:apply-templates/>
        </div>
    </xsl:template>

    <xsl:template match="cdm:program/cdm:infoBlock/cdm:subBlock[@userDefined = 'enSavoirPlus' and @blockLang = 'fr-FR']">
        <div class="alert alert-more">
            <h3>Pour en savoir + - liens utiles</h3>
            <xsl:apply-templates/>
        </div>
    </xsl:template>

    <xsl:template match="cdm:program/cdm:infoBlock/cdm:subBlock[@userDefined = 'informationsComplementaires' and @blockLang = 'fr-FR']">
        <div class="alert alert-info">
            <h3>Informations complémentaires</h3>
            <xsl:apply-templates/>
        </div>
    </xsl:template>

    <xsl:template match="cdm:orgUnit/cdm:orgUnit[cdm:orgUnitKind/cdm:subBlock/cdm:subBlock = 'Composante']">
        <p>
            <strong><xsl:value-of select="cdm:orgUnitName/cdm:text"/></strong><br/>
            <xsl:apply-templates select="cdm:contacts/cdm:contactData/cdm:adr"/>
        </p>
    </xsl:template>

    <xsl:template match="cdm:CDM/cdm:person">
        <p>
            <strong><xsl:value-of select="concat(cdm:name/cdm:given, ' ', cdm:name/cdm:family)"/></strong><br/>
            <xsl:apply-templates select="cdm:contactData/cdm:adr"/>
            <xsl:apply-templates select="cdm:contactData/cdm:telephone"/>
            <xsl:apply-templates select="cdm:contactData/cdm:email"/>
        </p>
    </xsl:template>

    <xsl:template match="cdm:contactData/cdm:adr">
        <xsl:if test="cdm:street">
           <xsl:value-of select="cdm:street"/><br/>
           <xsl:value-of select="cdm:pcode"/>&#160;
           <xsl:value-of select="cdm:locality"/><br/>
        </xsl:if>
    </xsl:template>

    <xsl:template match="cdm:contactData/cdm:telephone">
        <strong>Tél.&#160;:</strong>&#160;<xsl:value-of select="."/><br/>
    </xsl:template>

    <xsl:template match="cdm:person/cdm:contactData/cdm:email">
        <xsl:variable name="nom"><xsl:value-of select="concat(../../cdm:name/cdm:given, ' ', ../../cdm:name/cdm:family)"/></xsl:variable>
        <strong>Courriel&#160;:</strong>&#160;<a title="Envoyer un courriel à {$nom}" href="mailto:{.}"><xsl:value-of select="$nom"/></a><br/>
    </xsl:template>

    <xsl:template match="cdm:CDM/cdm:program/cdm:formOfTeaching">
        <div class="bloc fiche-diplome">
            <ul>
                <li><strong>Formation initiale&#160;:</strong>&#160;<xsl:value-of select="cdm:subBlock[@userDefined = 'formationInitiale']"/></li>
                <li><strong>Formation continue&#160;:</strong>&#160;<xsl:value-of select="cdm:subBlock[@userDefined = 'formationContinue']"/></li>
                <li><strong>En alternance&#160;:</strong>&#160;<xsl:value-of select="cdm:subBlock[@userDefined = 'formationEnAlternance']"/></li>
                <li><strong>À distance&#160;:</strong>&#160;<xsl:value-of select="cdm:subBlock[@userDefined = 'enseignementADistance']"/></li>
                <li><strong>Stage&#160;:</strong>&#160;<xsl:value-of select="cdm:subBlock[@userDefined = 'stage']"/></li>
                <li><strong>Stage à l'étranger&#160;:</strong>&#160;<xsl:value-of select="cdm:subBlock[@userDefined = 'stageEtranger']"/></li>
            </ul>
        </div>
    </xsl:template>

    <xsl:template match="cdm:listItem"><li><xsl:apply-templates/></li></xsl:template>
    
    <xsl:template match="cdm:br"><br/></xsl:template>
    
    <xsl:template match="cdm:emphasis"><em><xsl:apply-templates/></em></xsl:template>
    
    <xsl:template match="cdm:strong"><strong><xsl:apply-templates/></strong></xsl:template>
    
    <xsl:template match="cdm:subscript"><sub><xsl:apply-templates/></sub></xsl:template>
    
    <xsl:template match="cdm:superscript"><sup><xsl:apply-templates/></sup></xsl:template>
    
    <xsl:template match="cdm:altLangBlock"><span xml:lang="{@blockLang}" lang="{@blockLang}"><xsl:apply-templates/></span></xsl:template>
    
    <xsl:template match="cdm:webLink">
        <xsl:choose>
            <xsl:when test="@userDefined != ''">
                <xsl:choose>
                    <xsl:when test="@userDefined = 'reinscription'"><ul><li><a href="{cdm:href}" rel="nofollow">Vous inscrire ou vous ré-inscrire en ligne</a> (<strong>conseillé</strong>)</li></ul></xsl:when>
                    <!--<xsl:when test="@userDefined = 'reinscription'"><li><a href="{cdm:href}" rel="nofollow">Effectuer une réinscription en ligne</a></li></xsl:when>-->
                    <xsl:when test="@userDefined = 'candidature'"><ul><li><a href="{cdm:href}" rel="nofollow">Déposer un dossier de candidature</a></li></ul></xsl:when>
                    <xsl:when test="@userDefined = 'dossier'"><ul><li><a href="{cdm:href}" rel="nofollow">Déposer un dossier d'inscription sur rendez-vous</a></li></ul></xsl:when>
                </xsl:choose>
            </xsl:when>
            <xsl:otherwise>
                <xsl:choose>
                    <xsl:when test="starts-with(cdm:href, 'http://') or starts-with(cdm:href, 'https://') or starts-with(cdm:href, 'mailto:')">
                        <a>
                            <xsl:attribute name="href"><xsl:value-of select="cdm:href"/></xsl:attribute>
                            <xsl:if test="starts-with(cdm:href, 'http://siig')">
                                <xsl:attribute name="rel">nofollow</xsl:attribute>
                            </xsl:if>
                            <xsl:if test="contains(cdm:href, '.pdf')">
                            </xsl:if>
                            <xsl:choose>
                                <xsl:when test="cdm:linkName != ''"><xsl:value-of select="cdm:linkName"/></xsl:when>
                                <xsl:otherwise><xsl:value-of select="cdm:href"/></xsl:otherwise>
                            </xsl:choose>
                        </a>
                        <xsl:if test="contains(cdm:href, '.pdf') and (substring-after(cdm:href, '.pdf') = '')">&#160;(PDF)</xsl:if>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:value-of select="cdm:href"/>
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>
    
    <xsl:template match="cdm:subBlock[@blockLang = 'fr-FR']/cdm:header">
        <h3><xsl:apply-templates/></h3>
    </xsl:template>
    
    <xsl:template match="cdm:subBlock[@blockLang = 'fr-FR']/cdm:subBlock">
        <xsl:choose>
            <xsl:when test="@userDefined"/>
            <xsl:when test="count(./cdm:list) &gt; 0">
                <xsl:apply-templates/>
            </xsl:when>
            <xsl:otherwise>
                <p><xsl:apply-templates/></p>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>
    
    <xsl:template match="cdm:subBlock[@blockLang = 'fr-FR']/cdm:subBlock/cdm:list">
        <xsl:choose>
            <xsl:when test="@listType = 'numbered'">
                <ol><xsl:apply-templates/></ol>
            </xsl:when>
            <xsl:otherwise>
                <ul><xsl:apply-templates/></ul>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>
    
    <xsl:template match="cdm:list">
        <xsl:choose>
            <xsl:when test="@listType = 'numbered'">
                <ol><xsl:apply-templates/></ol>
            </xsl:when>
            <xsl:otherwise>
                <ul><xsl:apply-templates/></ul>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>
    
    <xsl:template match="cdm:listItem"><li><xsl:apply-templates/></li></xsl:template>
    
    <xsl:template match="/cdm:CDM/cdm:person">
        <p><strong><xsl:value-of select="cdm:name/cdm:given"/>&#160;<xsl:value-of select="cdm:name/cdm:family"/></strong></p>
        <xsl:if test="cdm:contactData/cdm:adr/cdm:street">
            <p>
                <xsl:value-of select="cdm:contactData/cdm:adr/cdm:street"/><br />
                <xsl:value-of select="cdm:contactData/cdm:adr/cdm:pcode"/>&#160;<xsl:value-of select="cdm:contactData/cdm:adr/cdm:locality"/>
            </p>
        </xsl:if>
        <xsl:if test="cdm:contactData/cdm:telephone">
            <p>Tél.&#160;: <xsl:value-of select="cdm:contactData/cdm:telephone"/></p>
        </xsl:if>
        <xsl:if test="cdm:contactData/cdm:email"><p><xsl:apply-templates select="cdm:contactData/cdm:email"></xsl:apply-templates></p></xsl:if>
    </xsl:template>
    
    <xsl:template match="cdm:email">
        <a><xsl:attribute name="href">mailto:<xsl:value-of select="."/></xsl:attribute>
            <xsl:attribute name="title">Envoyer un courriel à <xsl:value-of select="."/></xsl:attribute>Contacter par courriel</a>
    </xsl:template>

</xsl:stylesheet>