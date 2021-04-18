$(function () {
        $("body").on( "click", ".jsDataTableSelect", function() {
          if ($(this).attr('datatable-check') === 'checked') {
                $(this).addClass('fa-square-o').removeClass('fa-check-square-o');
                $(this).attr('datatable-check', 'unChecked');
            } else {
                $(this).addClass('fa-check-square-o').removeClass('fa-square-o');
                $(this).attr('datatable-check', 'checked');
            }

            var countCheckboxes = $(".jsDataTableSelect").length;
            var countCheckedCheckboxes = $(".jsDataTableSelect[datatable-check='checked']").length;

            if (countCheckedCheckboxes == 0) {
                $("#jsDataTableSelectAllOrNone").addClass('fa-square-o').removeClass('fa-check-square-o fa-minus-square-o');
                $("#jsDataTableSelectAllOrNone").attr('datatable-check', 'unChecked');
                $("#jsDataTableActionDelete").hide();
            } else if (countCheckboxes == countCheckedCheckboxes) {
                $("#jsDataTableSelectAllOrNone").addClass('fa-check-square-o').removeClass('fa-minus-square-o fa-square-o');
                $("#jsDataTableSelectAllOrNone").attr('datatable-check', 'checked');
                $("#jsDataTableActionDelete").show();
            } else if (countCheckboxes > countCheckedCheckboxes) {
                $("#jsDataTableSelectAllOrNone").addClass('fa-minus-square-o').removeClass('fa-check-square-o fa-square-o');
                $("#jsDataTableSelectAllOrNone").attr('datatable-check', 'unChecked');
                $("#jsDataTableActionDelete").show();
            }
        });
        $("body").on( "click", "#jsDataTableSelectAllOrNone", function() {
         if ($(this).attr('datatable-check') === 'checked') {
                $(this).addClass('fa-square-o').removeClass('fa-check-square-o fa-minus-square-o ');
                $(this).attr('datatable-check', 'unChecked');

                $(".jsDataTableSelect").addClass('fa-square-o').removeClass('fa-check-square-o');
                $(".jsDataTableSelect").attr('datatable-check', 'unChecked');
                $("#jsDataTableActionDelete").hide();
            } else {
                $(this).addClass('fa-check-square-o').removeClass('fa-square-o fa-minus-square-o ');
                $(this).attr('datatable-check', 'checked');

                $(".jsDataTableSelect").addClass('fa-check-square-o').removeClass('fa-square-o');
                $(".jsDataTableSelect").attr('datatable-check', 'checked');
                $("#jsDataTableActionDelete").show();
            }
        });
        $("body").on( "click", "#jsDataTableSelectAll", function() {
            $("#jsDataTableSelectAllOrNone").addClass('fa-check-square-o').removeClass('fa-square-o fa-minus-square-o ');
            $("#jsDataTableSelectAllOrNone").attr('datatable-check', 'checked');
            $(".jsDataTableSelect").addClass('fa-check-square-o').removeClass('fa-square-o');
            $(".jsDataTableSelect").attr('datatable-check', 'checked');
           if($(".jsDataTableSelect").length>0){
            $("#jsDataTableActionDelete").show();
        }else{
            $("#jsDataTableActionDelete").hide();
        }
        });
        $("body").on( "click", "#jsDataTableSelectNone", function() {
            $("#jsDataTableSelectAllOrNone").addClass('fa-square-o').removeClass('fa-check-square-o fa-minus-square-o ');
            $("#jsDataTableSelectAllOrNone").attr('datatable-check', 'checked');
            $(".jsDataTableSelect").addClass('fa-square-o').removeClass('fa-check-square-o');
            $(".jsDataTableSelect").attr('datatable-check', 'unChecked');
            $("#jsDataTableActionDelete").hide();

        });

     });
 function removeDatatableSelectedRow(rows) {
    $.each(rows, function(index, value) {
        $(".jsDataTableSelect[datatable-id='"+value+"']").closest("tr").remove();
    });
    $("#jsDataTableSelectAllOrNone").addClass('fa-square-o').removeClass('fa-check-square-o fa-minus-square-o ');
    $("#jsDataTableSelectAllOrNone").attr('datatable-check', 'checked');
    $("#jsDataTableActionDelete").hide();

 }
 function getDatatableSelectedRow() {
    var selectedElement= $(".jsDataTableSelect");
    var returnSelectedElement=[];
    selectedElement.each(function() {
        if($(this).attr('datatable-check')=='checked' && $(this).hasClass('fa-check-square-o')==true){
          returnSelectedElement.push($(this).attr('datatable-id'));
        }
    });
   return  returnSelectedElement;
 }

function convertToInt($data) {
    return parseInt(isNotEmptyNull($data));
}