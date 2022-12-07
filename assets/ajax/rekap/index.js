import baseUrl from "../base.js";
import basePath from "../domain.js";
import success from "../success.js";
import successOne from "../successOne.js";
import successTwo from "../successTwo.js";
import error from "../error.js";
import errorOne from "../errorOne.js";

$(document).on('click','.detail',function(){
    var absen = $(this).attr('data-absen');
   
    
    $.ajax({
        type:'POST',
        url:baseUrl+'/rekap/detail',
        data:{absen:absen},
        dataType:'json',
        success:function(resp){
            if(resp.status == 200){
                $('#detail').modal('show');
                $('#data').html(resp.output);
            }else{
                $('#detail').modal('hide');
                error('Peringatan',resp.msg);

            }
        }
    })
})