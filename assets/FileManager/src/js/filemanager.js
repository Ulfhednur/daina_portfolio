jQuery(document).ready(function ($) {
    let currentFolder = 0;
    let managerModal = UIkit.modal(document.getElementById('image-selector-modal'), '{"esc-close": false, "bg-close": true}');

    UIkit.util.on('#image-selector-modal', 'beforehide', function () {
        if (galleryId) {
            reloadPhotos();
        }
    });

    const spinnerModal = $('#spinner-modal');
    $('.image-selector-modal-opener').on('click', function (e) {
        e.preventDefault();
        ImageSelectorMultiselect = $(this).data('select-type');
        ImageSelectorInputName = $(this).data('selector-input-name');
        galleryId = $(this).data('gallery-id');
        $.ajax({
            url: adminUrl + '/media',
            type: 'POST',
            data: {
                select_type: ImageSelectorMultiselect,
                gallery_id: galleryId
            },
            dataType: 'json',
            success: function (data) {
                $('#image-selector-modal .uk-modal-dialog').html(data.html);
            },
            complete: function () {
                prepareDragDropArea();
                clickRemove();
                clickCreate();
                clickFolder('#folders-panel a[data-folder-id], #folders-breadcrumb a[data-folder-id]');
                editFolder();
                saveClick();
                editMedia('#files-aria a[data-item-id]');
                selectMediaItem();
                chooseMedia();
            }
        });
        managerModal.show();
    });

    const prepareDragDropArea = function () {
        const dragDropArea = document.getElementById('drag-drop-area');
        dragDropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dragDropArea.classList.add('drag-over');
        });
        dragDropArea.addEventListener('dragleave', () => {
            dragDropArea.classList.remove('drag-over');
        });
        dragDropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dragDropArea.classList.remove('drag-over');
            const files = e.dataTransfer.files;
            handleDroppedFiles(files);
        });

        function handleDroppedFiles(files) {
            const filePreviews = document.getElementById('file-previews');
            const mediaTabs = $('.media-manager-tabs li');
            const mediaClose = $('#image-selector-modal [uk-close]');
            mediaTabs.addClass('uk-disabled');
            mediaClose.addClass('uk-disabled');
            filePreviews.innerHTML = ``;
            let i = 0;
            for (const file of files) {
                if (file.type.startsWith('image/')) {
                    const previewBlock = document.createElement('div');
                    previewBlock.className = 'uk-grid-small';
                    previewBlock.setAttribute('uk-grid', '');

                    let preview = document.createElement('div');
                    preview.className = 'file-preview uk-width-1-6';

                    const image = document.createElement('img');
                    image.src = URL.createObjectURL(file);
                    preview.appendChild(image);
                    previewBlock.appendChild(preview);

                    let progressBlock = document.createElement('div');
                    progressBlock.className = 'uk-width-5-6 uk-flex uk-flex-column uk-flex-between';
                    progressBlock.textContent = file.name;
                    let uploadNotification = document.createElement('div');
                    uploadNotification.className = 'uk-text-success';
                    uploadNotification.setAttribute('id', 'upload-notification-' + i);
                    uploadNotification.innerText = '&nbsp;';
                    progressBlock.appendChild(uploadNotification);
                    let progress = document.createElement('progress');
                    progress.className = 'uk-progress uk-margin-remove';
                    progress.setAttribute('value', '0');
                    progress.setAttribute('max', '100');
                    progress.setAttribute('id', 'upload-progress-' + i);
                    progressBlock.appendChild(progress);
                    previewBlock.appendChild(progressBlock);

                    filePreviews.appendChild(previewBlock);
                    i++;
                }
            }

            const formData = new FormData()
            i = 0;
            let queueSize = 0;
            let uploadSlots = 3;
            let executeUpload = function (file) {
                if (uploadSlots) {
                    uploadSlots--;
                    let uploadProgress = document.getElementById('upload-progress-' + i.toString());
                    let uploadNotification = document.getElementById('upload-notification-' + i.toString());
                    UIkit.scroll(filePreviews, {offset: 240}).scrollTo(uploadProgress);
                    formData.append('file', file);
                    $.ajax({
                        url: adminUrl + '/media/create/' + currentFolder,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        xhr: function () {
                            const xhr = new XMLHttpRequest();
                            xhr.upload.addEventListener('progress', (e) => {
                                const percent = (e.loaded / e.total) * 100;
                                uploadProgress.setAttribute('value', percent.toFixed(2));
                            });
                            return xhr;
                        },
                        success: function (data) {
                            uploadNotification.innerText = 'Файл загружен.'
                            reloadPanels(data);
                        },
                        error: function (xhr) {
                            let errorMsg = JSON.parse(xhr.responseText).message;
                            UIkit.notification({
                                message: errorMsg,
                                status: 'danger',
                                pos: 'top-center'
                            });

                            uploadNotification.innerText = errorMsg;
                            uploadNotification.className = 'uk-text-danger';
                            uploadProgress.setAttribute('value', '0');
                        },
                        complete() {
                            uploadSlots++;
                            queueSize--;
                            if (queueSize === 0) {
                                mediaTabs.removeClass('uk-disabled');
                                mediaClose.removeClass('uk-disabled');
                            }
                        }
                    });

                    i++;
                } else {
                    setTimeout(function () {
                        executeUpload(file)
                    }, 300);
                }
            }

            for (const file of files) {
                queueSize++;
                executeUpload(file);
            }
        }
    }

    const saveClick = function () {
        $('.media-manager-form button[type="submit"]').on('click', function (e) {
            e.preventDefault();
            spinnerModal.addClass('visible');
            let adminForm = $('.media-manager-form');
            let formData = adminForm.serialize() + '&allow-multiselect=1';
            console.log(formData);
            let actionUrl = adminForm.attr('action');
            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (data) {
                    UIkit.notification({
                        message: 'Сохранено',
                        status: 'success',
                        pos: 'top-center',
                        timeout: 3000
                    });
                    reloadPanels(data);
                },
                error: function (xhr) {
                    UIkit.notification({
                        message: JSON.parse(xhr.responseText).message,
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 7000
                    });
                },
                complete: function () {
                    spinnerModal.hide();
                }
            });
        });
    }

    const editFolder = function () {
        let folderEditBtn = $('#files-aria a[data-folder-id]');
        folderEditBtn.on('click', function (e) {
            e.preventDefault();
            let folderId = $(this).attr('data-folder-id')
            $.ajax({
                url: adminUrl + '/folder/edit/' + folderId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    reloadPanels(data);
                },
                error: function (xhr) {
                    UIkit.notification({
                        message: JSON.parse(xhr.responseText).message,
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 7000
                    });
                }
            });
        });
    }

    const editMedia = function (selector) {
        let mediaEditBtn = $(selector);
        $('.media-manager-form').remove();
        mediaEditBtn.on('click', function (e) {
            e.preventDefault();
            let mediaId = $(this).attr('data-item-id')
            $.ajax({
                url: adminUrl + '/media/edit/' + mediaId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (selector === 'span.edit-in-gallery') {
                        data.selector = '#image-edit-panel';
                    }
                    reloadPanels(data);
                },
                error: function (xhr) {
                    UIkit.notification({
                        message: JSON.parse(xhr.responseText).message,
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 7000
                    });
                }
            });
        });
    }

    const clickFolder = function (selector) {
        let folderBtn = $(selector);
        folderBtn.on('click', function (e) {
            e.preventDefault();
            currentFolder = Number($(this).attr('data-folder-id'));
            let selectedFolder = $('#folders-panel li[data-folder-id="' + currentFolder + '"]');
            if (!selectedFolder.hasClass('uk-active')) {
                selectedFolder.addClass('uk-active');
            }
            $('#folders-panel li[data-folder-id]:not([data-folder-id="' + currentFolder + '"])').removeClass('uk-active');

            $.ajax({
                url: adminUrl + '/media/show/' + currentFolder + '?allow-multiselect=' + Math.abs(ImageSelectorMultiselect) + '&gallery_id=' + galleryId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    reloadPanels(data);
                },
                error: function (xhr) {
                    UIkit.notification({
                        message: JSON.parse(xhr.responseText).message,
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 7000
                    });
                }
            });
        });
    }

    const clickCreate = function () {
        $('#folder-create').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: adminUrl + '/folder/create/' + currentFolder + '?=allow_multiselect' + ImageSelectorMultiselect,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    reloadPanels(data);
                    UIkit.notification({
                        message: 'Каталог создан',
                        status: 'success',
                        pos: 'top-center',
                        timeout: 3000
                    });
                },
                error: function (xhr) {
                    UIkit.notification({
                        message: JSON.parse(xhr.responseText).message,
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 7000
                    });
                }
            });
        });
    }

    const clickRemove = function () {
        $('#btn-remove').on('click', function (e) {
            e.preventDefault();
            spinnerModal.addClass('visible');
            let checkedItems = $('input[type="checkbox"][data-item-type="media"]:checked');
            let checkedFolders = $('input[type="checkbox"][data-item-type="folder"]:checked');

            let Items = {
                Media: [],
                Folder: [],
                parent_id: currentFolder,
                allowMultiSelect: Math.abs(ImageSelectorMultiselect)
            };

            checkedItems.each(function () {
                Items.Media.push($(this).val());
            });

            checkedFolders.each(function () {
                Items.Folder.push($(this).val());
            });

            $.ajax({
                url: adminUrl + '/folder/delete',
                type: 'POST',
                data: Items,
                dataType: 'json',
                success: function (data) {
                    reloadPanels(data);
                    UIkit.notification({
                        message: 'Файл удалён',
                        status: 'success',
                        pos: 'top-center',
                        timeout: 3000
                    });
                },
                error: function (xhr) {
                    UIkit.notification({
                        message: JSON.parse(xhr.responseText).message,
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 7000
                    });
                },
                complete: function () {
                    spinnerModal.hide();
                }
            });
        });
    }

    const selectMediaItem = function () {
        let mediaItem = $('#files-aria .card input[type="checkbox"]');
        mediaItem.on('change', function () {
            let mediaItemWrapper = $(this).parent().parent();
            if (this.checked) {
                mediaItemWrapper.addClass('selected');
                if (galleryId && ImageSelectorMultiselect) {
                    $.ajax({
                        url: adminUrl + '/gallery/add-item/' + galleryId,
                        type: 'POST',
                        data: {
                            'media_id': $(this).val()
                        },
                        dataType: 'json',
                        success: function (data) {
                            reloadPhotos(data.html);
                        },
                        error: function (xhr) {
                            UIkit.notification({
                                message: JSON.parse(xhr.responseText).message,
                                status: 'danger',
                                pos: 'top-center',
                                timeout: 7000
                            });
                        }
                    });
                }
            } else {
                mediaItemWrapper.removeClass('selected');
                if (galleryId && ImageSelectorMultiselect) {
                    deleteFromGallery($(this).val());
                }
            }
        });
    }

    const deleteFromGalleryClick = function () {
        $('.remove-from-gallery').on('click', function () {
            let itemId = $(this).data('item-id')
            $(this).parent().parent().remove();
            deleteFromGallery(itemId);
        });
    }

    const enableSortable = function () {
        UIkit.sortable(document.getElementById('sortable-gallery-photos'));
        UIkit.util.on('#sortable-gallery-photos', 'stop', function () {
            reorderGallery();
        });
        LightBoxInit('#sortable-gallery-photos [uk-lightbox] > a');
        editMedia('span.edit-in-gallery');
    }

    const reorderGallery = function () {
        let galleryPhotos = $('#sortable-gallery-photos li');
        let i = 1;
        galleryPhotos.each(function () {
            $(this).find('.ordering-field-input').val(i);
            i++;
        });
    }

    const deleteFromGallery = function (itemId) {
        $.ajax({
            url: adminUrl + '/gallery/remove-item/' + galleryId,
            type: 'POST',
            data: {
                'media_id': itemId
            },
            dataType: 'json',
            success: function (data) {
                reloadPhotos(data.html);
            },
            error: function (xhr) {
                UIkit.notification({
                    message: JSON.parse(xhr.responseText).message,
                    status: 'danger',
                    pos: 'top-center',
                    timeout: 7000
                });
            }
        });
        reorderGallery();
    }

    if (ImageSelectorMultiselect) {
        enableSortable();
    }

    const chooseMedia = function () {
        $('#media-select').on('click', function (e) {
            e.preventDefault();
            if (ImageSelectorMultiselect === 0) {
                let selectedItem = $('input[type="radio"][data-item-type="media"]:checked');
                if (selectedItem.length === 0) {
                    UIkit.notification({
                        message: 'Пожалуйста, выберите файл',
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 7000
                    });
                } else {
                    $('input[name="' + ImageSelectorInputName + '"]').val(selectedItem.val());
                    let previewWrapper = $('#selected-image-preview');
                    previewWrapper.html('');
                    let previewImage = $('<img>');
                    previewImage.attr('src', selectedItem.parent().find('img').attr('src'));
                    previewWrapper.append(previewImage);
                    managerModal.hide();
                }
            } else {
                let selectedItems = $('input[type="checkbox"][data-item-type="media"]:checked');
                if (selectedItems.length === 0) {
                    UIkit.notification({
                        message: 'Пожалуйста, выберите файлы',
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 7000
                    });
                } else {
                    reloadPhotos();
                    managerModal.hide();
                }
            }
        });
    }

    const reloadPhotos = function (data = null) {
        let photosBlock = $('#gallery-selected-photos');
        if (data) {
            photosBlock.html(data.html);
            enableSortable();
            deleteFromGalleryClick();
        } else {
            $.ajax({
                url: adminUrl + '/gallery/show/' + galleryId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    photosBlock.html(data.html);
                    enableSortable();
                    deleteFromGalleryClick();
                }
            });
        }
    }

    const reloadPanels = function (data) {
        if (typeof data.selector === "undefined") {
            data.selector = '#info-panel';
        }
        if (data.folders) {
            $('#folders-panel').html(data.folders);
            clickFolder('#folders-panel a[data-folder-id]');
        }
        if (data.files) {
            $('#files-panel').html(data.files);
            LightBoxInit('#files-panel [uk-lightbox] > a');
            editFolder();
            editMedia('#files-aria a[data-item-id]');
            clickFolder('#folders-breadcrumb a[data-folder-id]');
            selectMediaItem();
        }
        if (data.form) {
            let formObj = $(data.form);
            $(data.selector).html('');
            $(data.selector).append(formObj);
            $('input[autofocus]').focus().select();
            saveClick();
        } else {
            $(data.selector).html('&nbsp;');
        }
    }

    function LightBoxInit (selector) {
        $(selector).on('click', function () {
            let lightBoxItem = UIkit.lightbox($(this), {});
            lightBoxItem.show();
        });
    }

    deleteFromGalleryClick();
});