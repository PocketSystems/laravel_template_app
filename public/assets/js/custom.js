$(function (){
    if(!window.dt){
        window.dt = {};
    }
    $('[data-table]').each((idx,element)=>{
        createDataTable(element)
    });

    $('.select2').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search options'
    })
    $('.datepicker').datepicker();


});

function createDataTable(elem) {
    let id = $(elem).attr("data-table")
    let dataUrl = $(elem).attr("data-url")
    let columns = $(elem).attr("data-cols")
    window.dt[id] = $(elem).DataTable({
        processing: true,
        serverSide: true,
        'ajax': dataUrl,
        "columns": JSON.parse(atob(columns)),
        responsive: true,
        order: [[0, "desc"]],
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_ records',
        }
    });

}

function delete_row(id, link, token,elem) {
    $.confirm({
        title: 'Delete Record?',
        content: 'This dialog will automatically trigger \'cancel\' in 6 seconds if you don\'t respond.',
        autoClose: 'cancelAction|8000',
        theme: 'dark',
        buttons: {
            deleteUser: {
                text: 'delete record',
                action: function () {
                    $.ajax({
                        url: link,
                        data: {
                            "_token": token,
                        },
                        type: 'DELETE',
                        success: function (result) {
                            let tables = $(elem).closest("[data-table]");
                            window.dt[$(tables[0]).attr("data-table")].ajax.reload();
                        }
                    });

                }
            },
            cancelAction: function () {
            }
        }
    });
}

function change_status(id, link, token,elem) {

    $.ajax({
        url: link,
        data: {
            "_token": token,
        },
        type: 'PUT',
        success: function (result) {
            let tables = $(elem).closest("[data-table]");
            window.dt[$(tables[0]).attr("data-table")].ajax.reload();
        }
    });
}

function deleteFile(id, link, token,image) {

    $.confirm({
        title: 'Delete Image?',
        content: 'This dialog will automatically trigger \'cancel\' in 6 seconds if you don\'t respond.',
        autoClose: 'cancelAction|8000',
        theme: 'dark',
        buttons: {
            deleteUser: {
                text: 'delete image',
                action: function () {
                    $.ajax({
                        url: link,
                        data: {
                            "_token": token,
                            "image":image
                        },
                        type: 'DELETE',
                        success: function (result) {
                            $('#image-field').removeClass('col-md-3')
                            $('#image-field').addClass('col-md-4')
                            $('#image-box').css('display','none')
                        }
                    });

                }
            },
            cancelAction: function () {
            }
        }
    });
}


function orderStatus(id, link, token,elem) {
    $.confirm({
        title: 'Update Status',
        content: 'This dialog will automatically trigger \'cancel\' in 6 seconds if you don\'t respond.',
        autoClose: 'cancelAction|8000',
        theme: 'dark',
        buttons: {
            deleteUser: {
                text: 'Update Status',
                action: function () {
                    $.ajax({
                        url: link,
                        data: {
                            "_token": token,
                        },
                        type: 'PUT',
                        success: function (result) {
                            let tables = $(elem).closest("[data-table]");
                            window.dt[$(tables[0]).attr("data-table")].ajax.reload();
                        }
                    });

                }
            },
            cancelAction: function () {
            }
        }
    });
}
function PrintElem(elem)
{
    var mywindow = window.open('', 'PRINT', 'height=400,width=600');

    mywindow.document.write('<html><head>');
    mywindow.document.write('</head><body >');
    mywindow.document.write('<h1>' + document.title  + '</h1>');
    mywindow.document.write(document.getElementById(elem).innerHTML);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();

    return true;
}
