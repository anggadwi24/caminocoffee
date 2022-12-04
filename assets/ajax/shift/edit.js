import baseUrl from "../base.js";
import basePath from "../domain.js";
import success from "../success.js";
import successOne from "../successOne.js";
import successTwo from "../successTwo.js";
import error from "../error.js";
import errorOne from "../errorOne.js";


$(document).on('click','.edit',function(){
    var id = $(this).attr('data-id');
    $.ajax({
        type:'POST',
        url:baseUrl+'/shift/edit',
        data:{id,id},
        dataType:'json',
        success:function(resp){
            if(resp.status == 200){
                $('#edit_shift').modal('show');
                $('#shift').val(resp.arr.shift);
                $('#in').val(resp.arr.in);
                $('#out').val(resp.arr.out);
                $('#formEdit').attr('action',resp.arr.form);

            }else{
                error('Peringatan',resp.msg);
            }
        }

    })
})