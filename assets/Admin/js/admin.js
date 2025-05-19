jQuery(document).ready(function ($) {
    const adminForm = $('#admin-form');
    const controllerUrl = adminForm.attr('action');

    const doListAction = function (buttonAction) {
        if (($('#admin-form input[type="checkbox"][name]').length && $('#admin-form input[type="checkbox"][name]:checked').length) || buttonAction === 'edit') {
            let formAction = controllerUrl + '/' + buttonAction;
            adminForm.attr('action', formAction);
            adminForm.submit();
        }
    }
    const doEditAction = function (buttonAction) {
        let formAction = controllerUrl + '/' + buttonAction;
        adminForm.attr('action', formAction);
        adminForm.submit();
    }

    $('.check-all').click(function () {
        let selector = $(this).is(':checked') ? ':not(:checked)' : ':checked';

        $('#admin-form input[type="checkbox"]' + selector).each(function () {
            $(this).trigger('click');
        });
    });

    $('.publish-switcher > label').click(function () {
        if (!$(this).hasClass('active')) {
            $(this).addClass('active');
            $(this).siblings().removeClass('active');
        }
    });

    $('#item_created_date').datetimepicker({
        format: 'd.m.Y H:i:00'
    });

    $('.inline-publish-change').on('click', function (e) {
        e.preventDefault();
        let publicationStatus = $(this).data('published') === true;
        let rowNumber = Number($(this).data('row'));
        let uncheckCheckboxes = $('list-selector:checked:not([value="' + rowNumber + '"])');
        let checkboxId = 'ids-' + rowNumber;
        let buttonAction = '';

        uncheckCheckboxes.trigger('click');

        if (!document.getElementById(checkboxId).checked) {
            $('#' + checkboxId).trigger('click');
        }

        if (publicationStatus) {
            buttonAction = 'unpublish';
        } else {
            buttonAction = 'publish';
        }

        doListAction(buttonAction);
    });

    $('button[data-list-action]').on('click', function (e) {
        e.preventDefault();
        doListAction($(this).data('list-action'));
    });

    $('button[data-edit-action]').on('click', function (e) {
        e.preventDefault();
        let buttonAction = $(this).data('edit-action');
        if ($(this).data('add-id') === true) {
            let itemId = $('#item-id').val();
            if (itemId.length) {
                buttonAction += '/' + itemId;
            }
        }

        doEditAction(buttonAction);
    });

    $('#cancel-button').on('click', function (e) {
        e.preventDefault();
        window.location.replace(controllerUrl);
    });

    UIkit.util.on('tbody[uk-sortable]', 'stop', function () {
        let galleryPhotos = $('tbody[uk-sortable] > tr');
        let adminForm = $('#admin-form');
        let i = 1;
        galleryPhotos.each(function () {
            $(this).find('.ordering-field-input').val(i);
            i++;
        });

        $.ajax({
            url: adminForm.attr('action') + '/order',
            type: 'POST',
            data: adminForm.serialize(),

        });
    });

    $('select[name="pageSize"]').on('change', function(){
        $('#admin-form').submit();
    });
});