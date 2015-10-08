$(function () {
    'use strict';

    $('#confirmDeleteModal').on('show.bs.modal', function (e) {
        var itemName = $(e.relatedTarget).data('item-name');
        var deleteURL = $(e.relatedTarget).data('delete-url');
        $(this).find('#modelDeleteItemName').html(itemName);
        $(this).find('#modalDeleteLink').attr("href", deleteURL);
    });
});
