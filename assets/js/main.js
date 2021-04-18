$(function () {

    $("html").on("dragover", function (e) {
        e.preventDefault();
        e.stopPropagation();
        $("h1").text("Drag here");
    });

    $("html").on("drop", function (e) {
        e.preventDefault();
        e.stopPropagation();
    });


    $('.upload-area').on('dragenter', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $("h1").text("Drop");
    });

    $('.upload-area').on('dragover', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $("h1").text("Drop");
    });

    var fd = '';
    $('.upload-area').on('drop', function (e) {
        $('.error').hide();
        e.stopPropagation();
        e.preventDefault();
        $("h1").text("Upload");
        var file = e.originalEvent.dataTransfer.files;
        fd = new FormData();
        fd.append('file', file[0]);
    });

    $("#uploadfile").click(function () {
        $("#file").click();
    });

    $("#file").change(function () {
        $('.error').hide();
        fd = new FormData();
        var files = $('#file')[0].files[0];
        fd.append('file', files);
    });
    $("#uploadProductCsv").click(function () {
        if (fd != '') {
            uploadData(fd);
        }else{
            $('.error').show();
            $('.error').text("Please Select File");
        }
    });

});

function uploadData(formdata) {
    $.ajax({
        url: 'product_csv_ajax.php',
        type: 'post',
        data: formdata,
        contentType: false,
        processData: false,
        dataType: 'json',
        beforeSend: function () {
            $('#ajaxProcessing').show();
            $('html,body').css('cursor', 'wait');
        },
        complete: function () {
            $('#ajaxProcessing').hide();
            $('html,body').css('cursor', 'auto');
        },
        success: function (response) {
            if (response.status === true) {
                $('.error').hide();
                window.location.reload();
            } else if (response.status === false) {
                $('.error').show();
                $('.error').text(response.message);
            }
        }
    });
}

$(document).delegate("#jsDataTableActionDelete", "click", function () {
    var selectedRow = getDatatableSelectedRow();
    swal({
        title: 'Are you sure?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete selected rows',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            $.ajax({
                url: "product_csv_ajax.php",
                type: "POST",
                dataType: 'json',
                data: {id: selectedRow},
                success: function (response) {
                    if (response.status === false) {
                        swal("Oops", "We couldn't connect to the server!", "error");
                    } else if (response.status === true) {
                        removeDatatableSelectedRow(selectedRow);
                        window.location.reload();
                    }
                }
            })
        },
        allowOutsideClick: false
    }).catch(swal.noop);
});
