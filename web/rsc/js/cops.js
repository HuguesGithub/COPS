$(document).ready(function() {
    $('html').height('100%');
    $('body').height('100%');

    ////////////////////////////////////////////////
    // Gestion du panel login
    if($('.login-panel').length!=0) {
        addLoginPageEvents();
    }
    ////////////////////////////////////////////////

    ////////////////////////////////////////////////
    // Gestion des events
    // Sur les data-trigger="click"
    $('.ajaxAction[data-trigger="click"]').on('click', function(){
        ajaxActionClick($(this));
    });
    // Sur les data-trigger="change"
    $('.ajaxAction[data-trigger="change"]').on('change', function(){
        ajaxActionChange($(this));
    });
    // Sur les data-trigger="enter"
    $('.ajaxAction[data-trigger="enter"]').on('keyup', function(e){
        if (e.key=='Enter' || e.keyCode==13) {
            $('button[data-target="#'+$(this).prop('id')+'"]').trigger('click');
        }
    });
    // Sur les data-trigger="keyup"
    $('.ajaxAction[data-trigger="keyup"]').on('keyup', function(){
        ajaxActionKeyUp($(this));
    });
    ////////////////////////////////////////////////

    ///////////////////////////////////////////////////
    // Start Tchat refresh
    // On cherche le bouton de refresh éventuel dans l'écran
    // Les boutons de refresh de tchat ont un data-ajax="refresh"
    if ($('button[data-ajax="refresh"').length!=0) {
        blnBlockTchatNotifications = true;
        tchatHistoric = [];
        $('button[data-ajax="refresh"').each(function(){
            let obj = $(this);
            let target = obj.data('target');
            $(target).scrollTop($(target).scrollTop()+$(target+' > div:last').position().top)

            timer = setInterval(function() { refreshTchat(obj, true); }, 15000);
        });
    }
    // Gestion du refresh des Notifications
    timerNavigation = setInterval(function() { checkNotifications(); }, 15000);
    ///////////////////////////////////////////////////
    
});
////////////////////////////////////////////////
// Déclaration de variables nécessaies au global
// Timer des éventuels refresh automatiques
let timer = null;
// Spécifique aux éléments de la navigation
let timerNavigation = null;
// Bloquer ou non le refresh des Notifications de Tchat quand on est sur la page de Tchat
let blnBlockTchatNotifications = false;
// Historique de Tchat
let tchatHistoric = null;
////////////////////////////////////////////////

////////////////////////////////////////////////
// Gestion du panel login
// Initialement, il est invisible, si on bouge la souris ou qu'on appuie sur une touche, il apparaît.
function addLoginPageEvents() {
    $(document).bind('mousemove', function(e) {
        $('.login-panel').addClass('active');
      });
      $(window).bind('keydown', function(e){
        $('.login-panel').addClass('active');
      });
}
////////////////////////////////////////////////

////////////////////////////////////////////////
// Gestion affichage puis disparition des toasts.
function displayToast(value) {
    $('#toastPlacement').append(value);
    $('#toastPlacement .toast:last-child').delay(5000).hide(0);
}
////////////////////////////////////////////////

////////////////////////////////////////////////
// Gestion des events change
function ajaxActionChange(obj) {
    let id = obj.attr('id');
    let actions = obj.data('ajax').split(',');
    for (let oneAction of actions) {
        switch (oneAction) {
            case 'saveData' :
                saveData(obj);
                break;
            default :
                console.log(oneAction+" n'est pas une action définie pour ajaxActionChange.");
                break;
        }
    }
}
////////////////////////////////////////////////

////////////////////////////////////////////////
// Gestion des events click
function ajaxActionClick(obj) {
	let actions = obj.data('ajax').split(',');
    if (!obj.hasClass('disabled')) {
        for (let oneAction of actions) {
    	    switch (oneAction) {
                // Poster un message dans le tchat
                case 'tchat' :
                    obj.addClass('disabled').addClass('fa-spin');
                    sendTchat(obj);
                break;
                // Rafraichir le tchat
                case 'refresh':
                    obj.addClass('disabled').addClass('fa-spin');
                    refreshTchat(obj, false);
                break;
                // Suppression d'une adresse d'un individu
                case 'deleteGuyAddress' :
                    obj.addClass('disabled');
                    deleteGuyAddress(obj);
                break;
                case 'addressDropdown' :
                    findAddress(obj);
                    break;
                case 'cleanGuyAddress' :
                    cleanGuyAddress();
                    break;
                case 'insertGuyAddress' :
                    obj.addClass('disabled');
                    insertGuyAddress();
                    break;
                default :
                    console.log(oneAction+" n'est pas une action définie pour ajaxActionClick.");
                    break;
    		}
        }
	}
}
////////////////////////////////////////////////

////////////////////////////////////////////////
// Gestion des events keyUp
function ajaxActionKeyUp(obj) {
    let actions = obj.data('ajax').split(',');
    if (!obj.hasClass('disabled')) {
        for (let oneAction of actions) {
            switch (oneAction) {
                // Poster un message dans le tchat
                case 'filter' :
                    filterDropdown(obj);
                break;
                default :
                    console.log(oneAction+" n'est pas une action définie pour ajaxActionKeyUp.");
                    break;
            }
        }
    }
}
////////////////////////////////////////////////

///////////////////////////////////////////////////
function filterDropdown(obj) {
    // obj est un input dont la valeur doit filtrer le contenu d'un dropdown associé.
    let value = obj.val().toUpperCase();
    let target = obj.data('target');
    // On va juste cacher les valeurs qui ne correspondent pas au filtre.
    // On recherche %valeur%
    $(target+' li').each(function(){
        let valElement = $(this).data('value');
        if (valElement.includes(value)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}
///////////////////////////////////////////////////

///////////////////////////////////////////////////
// FONCTIONS RELATIVES AUX ADRESSES
///////////////////////////////////////////////////
function insertGuyAddress() {
    let data = {
        'action': 'dealWithAjax',
        'ajaxAction': 'insertGuyAddress',
        'number': $('#number').val(),
        'streetDirection': $('#streetDirection').val(),
        'streetName': $('#streetName').val(),
        'streetSuffix': $('#streetSuffix').val(),
        'streetSuffixDirection': $('#streetSuffixDirection').val(),
        'zipCode': $('#zipCode').val(),
        'guyId': $('input[name="id"]').val(),
    };
    $.post(
        ajaxurl,
        data,
        function(response) {}
    ).done(function(response) {
        location.reload();
    });
}

function cleanGuyAddress() {
    $('#number').val('');
    $('#streetDirection').val('');
    $('#streetName').val('');
    $('#streetSuffix').val('');
    $('#streetSuffixDirection').val('');
    $('#zipCode').val('');
    filterDropdown($('#streetName'));
}

function deleteGuyAddress(obj) {
    let data = {
        'action': 'dealWithAjax',
        'ajaxAction': 'deleteGuyAddress',
        'id': obj.attr('id')
    };
    $.post(
        ajaxurl,
        data,
        function(response) {}
    ).done(function(response) {
        obj.closest('li').remove();
    });
}

function findAddress(obj) {
    let value = obj.html();
    let target = obj.data('target');
    $(target).val(value);

    let objReturned = null;
    let data = {
        'action': 'dealWithAjax',
        'ajaxAction': 'findAddress',
        'streetDirection': $('#streetDirection').val(),
        'streetName': $('#streetName').val(),
        'streetSuffix': $('#streetSuffix').val(),
        'streetSuffixDirection': $('#streetSuffixDirection').val(),
        'zipCode': $('#zipCode').val(),
    };
    $.post(
        ajaxurl,
        data,
        function(response) {}
    ).done(function(response) {
        try {
            objReturned = JSON.parse(response);
            // Pour chaque élément de l'objet returned, on va mettre à jour les dropdown correspondantes.
            $('#dropDownstreetDirection').html(objReturned.dropDownstreetDirection);
            $('#streetDirection').val($('#dropDownstreetDirection li').length==1 ? $('#dropDownstreetDirection li a').html() : '');

            $('#dropDownstreetName').html(objReturned.dropDownstreetName);
            $('#streetName').val($('#dropDownstreetName li').length==1 ? $('#dropDownstreetName li a').html() : '');

            $('#dropDownstreetSuffix').html(objReturned.dropDownstreetSuffix);
            $('#streetSuffix').val($('#dropDownstreetSuffix li').length==1 ? $('#dropDownstreetSuffix li a').html() : '');

            $('#dropDownstreetSuffixDirection').html(objReturned.dropDownstreetSuffixDirection);
            $('#streetSuffixDirection').val($('#dropDownstreetSuffixDirection li').length==1 ? $('#dropDownstreetSuffixDirection li a').html() : '');

            $('#dropDownzipCode').html(objReturned.dropDownzipCode);
            $('#zipCode').val($('#dropDownzipCode li').length==1 ? $('#dropDownzipCode li a').html() : '');

            $('.ajaxAction[data-trigger="click"]').unbind().on('click', function(){
                ajaxActionClick($(this));
            });
        } catch(e) {
        }
    });
}

///////////////////////////////////////////////////
// FONCTIONS RELATIVES AUX NOTIFICATIONS
///////////////////////////////////////////////////
function checkNotifications() {
    if (blnBlockTchatNotifications) {
        if ($('.fa-comment').siblings().length!=0) {
            $('.fa-comment').siblings().remove();
        }
        return;
    }
    let obj = null
    let data = {'action': 'dealWithAjax', 'ajaxAction': 'checkNotif'};
    $.post(
        ajaxurl,
        data,
        function(response) {}
    ).done(function(response) {
        try {
            obj = JSON.parse(response);
            if (obj.comment && obj.comment!='') {
                if ($('.fa-comment').siblings().length!=0) {
                    $('.fa-comment').siblings().remove();
                }
                $('.fa-comment').parent().append(obj.comment);
            }
        } catch(e) {
            
        }
    });
}
///////////////////////////////////////////////////

///////////////////////////////////////////////////
// FONCTIONS RELATIVES AU TCHAT
///////////////////////////////////////////////////
//Permet d'envoyer une instruction via l'inerface de Tchat
function sendTchat(obj) {
    let id = obj.attr('id');
    let ajaxAction = obj.data('ajax');
    let target = obj.data('target');
    let value = $(target).val();
    let data = {'action': 'dealWithAjax', 'ajaxAction': ajaxAction, 'value': value};
    // On envoie par ajax les informations saisies
    $.post(
        ajaxurl,
        data,
        function(response) {
            try {
                obj = JSON.parse(response);
            } catch(e) {
                
            }
        }
    ).done(function(response) {
        // Je n'ai envie de garder que les commandes en fait...
        if (value.substr(0, 1)=='/') {
            tchatHistoric.push(value);
        }
        $(target).val('');
        $('#'+id).removeClass('disabled').removeClass('fa-spin');
        try {
            obj = JSON.parse(response);
            for (let oneElement of obj.refresh) {
                if (oneElement.target=='toastContent') {
                    if (!blnAutoRefresh) {
                        displayToast(oneElement.content);
                    }
                } else if (oneElement.type=='replace') {
                    $('#'+oneElement.target).html(oneElement.content);
                } else if (oneElement.type=='append') {
                    $('#'+oneElement.target).append(oneElement.content);
                    $('#'+oneElement.target).scrollTop($('#'+oneElement.target).scrollTop()+$('#'+oneElement.target+' > div:last').position().top);
                }
            }
        } catch(e) {
            $('#'+id).removeClass('disabled').removeClass('fa-spin');
        }
    });
}
///////////////////////////////////////////////////
// Permet de rafraichir le dialog du Tchat
function refreshTchat(obj, blnAutoRefresh) {
    let id = obj.attr('id');
    let ajaxAction = obj.data('ajax');
    let target = obj.data('target');
    let refreshed = $(target+' > div:last').data('refreshed');
    let data = {'action': 'dealWithAjax', 'ajaxAction': ajaxAction, 'refreshed': refreshed};
    // On envoie par ajax les informations saisies
    $.post(
        ajaxurl,
        data,
        function(response) {
            try {
                obj = JSON.parse(response);
            } catch(e) {
                $('#'+id).removeClass('disabled').removeClass('fa-spin');
            }
        }
    ).done(function(response) {
        $('#'+id).removeClass('disabled').removeClass('fa-spin');
        try {
            obj = JSON.parse(response);
            for (let oneElement of obj.refresh) {
                if (oneElement.target=='toastContent') {
                    if (!blnAutoRefresh) {
                        displayToast(oneElement.content);
                    }
                } else if (oneElement.type=='replace') {
                    $('#'+oneElement.target).html(oneElement.content);
                } else if (oneElement.type=='append') {
                    $('#'+oneElement.target).append(oneElement.content);
                    $('#'+oneElement.target).scrollTop($('#'+oneElement.target).scrollTop()+$('#'+oneElement.target+' > div:last').position().top);
                }
            }
        } catch(e) {
            $('#'+id).removeClass('disabled').removeClass('fa-spin');
        }
    });
}
///////////////////////////////////////////////////

///////////////////////////////////////////////////
// FONCTIONS AUTRES
///////////////////////////////////////////////////

// Permet de sauvegarder dynamiquement des champs.
function saveData(obj) {
    let field = obj.attr('id');
    let value = obj.val();
    let id = obj.data('objid');
    let data = {'action': 'dealWithAjax', 'ajaxAction': 'saveData', 'field': field, 'value': value, 'id': id};
  
    // On a un appel ajax pour rechercher les équivalences au numéro
    $.post(
        ajaxurl,
        data,
        function(response) {
            try {
                obj = JSON.parse(response);
            } catch (e) {
                console.log("error: "+e);
                console.log(response);
            }
        }
    ).done(function(response) {
        obj = JSON.parse(response);
        displayToast(obj.toastContent);
    });
}
///////////////////////////////////////////////////
