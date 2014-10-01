/*jslint browser: true*/
/*global $, jQuery, alert, console*/
/*jslint node: true */

"use strict";
var confirmText = 'Zamierzasz usunąć element, jesteś pewien? ';
var errorText = 'Wystąpił straszny błąd: ';
var successText = 'Wykonano!';
function deleteButtonRemoveTR(button, confirmText, successText, errorText) {
    if (confirm(confirmText)) {
        var toRemove = button.closest('tr');
        $.post(
                button.attr('href'),
                function (data) {
                    if (data.success) {
                        alert(successText);
                        $(toRemove).remove();
                    } else {
                        alert(errorText + data.error + ' :(');
                    }
                });
    }
}

function executeButton(button, confirmText, successText, errorText) {
    if (confirm(confirmText)) {
        $.post(
                button.attr('href'),
                function (data) {
                    if (data.success) {
                        alert(successText);
                    } else {
                        alert(errorText + data.error + ' :(');
                    }
                });
    }
}