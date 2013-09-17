declare namespace cdm="http://cdm-fr.fr/2006/CDM-frSchema";

let $unistra := 'FR_RNE_0673021V_OR'

return <secteurs-activite>{

for $s in distinct-values(collection('/db/CDM-2009')/cdm:CDM[cdm:orgUnit/@id = $unistra]/cdm:program/cdm:qualification/cdm:qualificationDescription/cdm:subBlock[@userDefined = 'secteursActiviteUNERA'])
order  by $s
return <secteur-activite>{$s}</secteur-activite>

}</secteurs-activite>