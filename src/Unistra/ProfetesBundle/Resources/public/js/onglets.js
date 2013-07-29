$('document').ready(function() {

    //au chargement, sélectionner le premier onglet
    premierOnglet = $('nav#menu-onglets li')[0];
    activerOnglet(premierOnglet);

    $('nav#menu-onglets li').on('click', function(e) {
        activerOnglet($(this));
        return false;
    })

});


function activerOnglet(onglet) {
    //id = partie après profetes-
    ongletId = $(onglet).attr('id').substring(9);

    //remise à zéro
    $('nav#menu-onglets li').removeClass('active');
    $('div.profetes-bloc').hide();

    //affichage de l'onglet cliqué
    $(onglet).addClass('active');
    $('div#profetes-bloc-' + ongletId).show();
}
