function initWidget(settings) {
    function each(arr, callback) {
        for (let i = 0; i < arr.length; i++) {
            callback.call(arr, arr[i], i, arr);
        }
        return arr;
    }

    var avatar = document.getElementById('image-result-' + settings.attribute);
    var image = document.getElementById('image-selected-' + settings.attribute);
    var input = document.getElementById('image-input-' + settings.attribute);
    var $progress = $('#progress-' + settings.attribute + '');
    var $progressBar = $('#progress-bar-' + settings.attribute + '');
    var $alert = $('#alert-' + settings.attribute + '');
    var $modal = $('#modal-cropper-' + settings.attribute + '');
    var previews = document.querySelectorAll('.preview');
    var cropper;
    $('[data-toggle="tooltip"]').tooltip();
    input.addEventListener('change', function (e) {
        var files = e.target.files;
        var done = function (url) {
            // input.value = '';
            image.src = url;
            $alert.hide();
            $modal.modal({
                backdrop: 'static',
                keyboard: false
            }).show();
        };
        var reader;
        var file;
        var url;
        if (files && files.length > 0) {
            file = files[0];
            if (file.size > settings.maxSize) {
                $alert.show().addClass('alert-warning').text(settings.size_error_text).delay(5000).slideDown(1000, function () {
                    $(this).hide();
                });
            } else if (settings.allowedExtensions.indexOf(file.name.replace(/^.*\./, '')) === -1) {
                $alert.show().addClass('alert-warning').text(settings.ext_error_text).delay(5000).slideDown(1000, function () {
                    $(this).hide();
                });
            } else if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });
    $modal.on('shown.bs.modal', function () {
        var aspectRatio;
        if (settings.aspectRatio)
            aspectRatio = settings.aspectRatio;
        else
            aspectRatio = settings.width / settings.height;
        if (settings.free)
            aspectRatio = null;
        cropper = new Cropper(image, {
            aspectRatio: aspectRatio,
            viewMode: 0,
            zoomable: false,
            ready: function () {
                var clone = this.cloneNode();
                clone.className = '';
                each(previews, function (elem) {
                    elem.appendChild(clone.cloneNode());
                });
                var cropper = this.cropper;
                var imageData = cropper.getCroppedCanvas({});
                each(previews, function (elem) {
                    var previewImage = elem.getElementsByTagName('img').item(0);
                    if (previewImage)
                        previewImage.src = imageData.toDataURL();
                });
            },
            crop: function (event) {
                var cropper = this.cropper;
                var imageData = cropper.getCroppedCanvas({});
                each(previews, function (elem) {
                    var previewImage = elem.getElementsByTagName('img').item(0);
                    if (previewImage)
                        previewImage.src = imageData.toDataURL();
                });
            },
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
    });

    document.getElementById('crop-button-' + settings.attribute + '').addEventListener('click', function () {
        $modal.modal('hide');
        if (cropper) {
            $progress.show();
            $alert.removeClass('alert-success alert-warning');
            var croppedData = cropper.getData();
            var formData = new FormData();
            formData.append(settings.name, input.files[0]);
            formData.append('width', settings.width);
            formData.append('height', settings.height);
            formData.append('w', croppedData.width);
            formData.append('h', croppedData.height);
            formData.append('x', croppedData.x);
            formData.append('y', croppedData.y);
            $.ajax(settings.url, {
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function () {
                    var xhr = new XMLHttpRequest();
                    xhr.upload.onprogress = function (e) {
                        var percent = '0';
                        var percentage = '0%';
                        if (e.lengthComputable) {
                            percent = Math.round((e.loaded / e.total) * 100);
                            percentage = percent + '%';
                            $progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
                        }
                    };
                    return xhr;
                },
                success: function (data) {
                    if (data.error) {
                        $alert.show().addClass('alert-warning').text(data.error).delay(5000).slideDown(1000, function () {
                            $(this).hide();
                        });
                    } else {
                        avatar.src = settings.prefix_url + data.filelink;
                        $('#cropper-input-' + settings.attribute).val(data.filelink);
                        $alert.show().addClass('alert-success').text(settings.upload_success).delay(5000).slideDown(1000, function () {
                            $(this).hide();
                        });
                    }
                },
                error: function () {
                    $alert.show().addClass('alert-warning').text(settings.upload_error).delay(5000).slideDown(1000, function () {
                        $(this).hide();
                    });
                },
                complete: function () {
                    input.value = '';
                    $progress.hide();
                },
            });
        }
    });
}