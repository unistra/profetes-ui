var scenario = 1;
var selections = [['Lev', 'Disc'], ['Disc', 'Lev'], ['Prof', 'Lev']];
var url = '/profetes-v2/recherche-assistee/';
var paths = {'100': 'types-diplomes',
             '110': 't/%1/disciplines',
             '111': 't/%2/d/%1',
             '200': 'disciplines',
             '210': 'd/%1/types-diplomes',
             '211': 't/%1/d/%2',
             '300': 'objectifs-professionnels',
             '310': 'o/%1/types-diplomes',
             '311': 'o/%2/t/%1',
            };
var msgAttente = '<p><img src="http://www.unistra.fr/fileadmin/templates/unistra/images/icones/focus/loading.gif" height="16" width="16" align="middle">Chargement en cours, merci de patienter</p>';

function updateContent(control, url) {
    $('div#res').empty().html(msgAttente).show();
    $('#' + control).empty().hide();
    $.get(url, function(data) {
        $('div#res').empty();
        $('#' + control).html(data).show();
        if (control == 'res') {
            $('p.legende').show();
        }
    });
}

function showScenario(scenario, combos) {
    $('#fScen' + scenario).show();
    $('#p' + combos[0] + scenario).show();
    $('#p' + combos[1] + scenario).hide();
}

function hideScenario(scenario, combos) {
    $('#fScen' + scenario).hide();
    $('#fScen' + scenario + ' p').hide();
}

function switchScenario(scenario, combos) {
    var scenari = [0, 0, 0];
    if (scenario) {
        scenari[scenario -1] = 1;
    }

    //cacher la légende EAD
    $('p.legende').hide();

    for (var i = 0; i < scenari.length; i++) {
        var selectedScenarioId = 'div#scenario li a#scen' + (i + 1);
        if (scenari[i]) {
            showScenario(i + 1, combos);
            $(selectedScenarioId).addClass('checked');
        } else {
            $(selectedScenarioId).removeClass('checked');
            hideScenario(i + 1, combos);
        }
    }

    //charger pour la première liste déroulante
    var urlOptions = url + paths[scenario +'00'];
    var control = 'cbo' + selections[scenario -1][0] + scenario;
    updateContent(control, urlOptions);
}


$(document).ready(function() {

    //première chose à faire : cacher ce qui n'est pas utile
    $('div#scenario').show();

    switchScenario(scenario, selections[0]);

    $('div#scenario ul a').click(function() {
        scenario = parseInt($(this).attr('id').substring(4));
        var choix = selections[scenario -1][0];
        switchScenario(scenario, selections[scenario -1]);
        return false;
    });

    $('select.cbo1').change(function() {
        var selected = $(this).val();
        var control = selections[scenario -1][1] + scenario;
        $('p.legende').hide();
        if (selected && scenario) {
            var loadUrl = url + paths[scenario + '10'].replace('%1', encodeURIComponent(selected));
            updateContent('cbo' + control, loadUrl);
            $('#p' + control).show();
        } else {
            $('div#res').empty().hide();
            $('#p' + control).hide();
        }
    });

    $('select.cbo2').change(function() {
        var parentSelected = $('#fScen' + scenario + ' select.cbo1').val();
        var selected = $(this).val();
        if (selected && scenario && parentSelected) {
            var loadUrl = url + paths[scenario + '11'].replace('%1', encodeURIComponent(selected)).replace('%2', encodeURIComponent(parentSelected));
            var control = 'res';
            updateContent(control, loadUrl);
        } else {
            $('div#res').empty().hide();
            $('p.legende').hide();
        }
    });
});
