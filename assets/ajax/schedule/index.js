import baseUrl from "../base.js";
import basePath from "../domain.js";
import success from "../success.js";
import successOne from "../successOne.js";
import successTwo from "../successTwo.js";
import error from "../error.js";
import errorOne from "../errorOne.js";

$(document).on('click','.detail',function(){
    var username = $(this).attr('data-username');
    var id = $(this).attr('data-id');
    var date = $(this).attr('data-date');
    
    $.ajax({
        type:'POST',
        url:baseUrl+'/schedule/detail',
        data:{username:username,id:id,date:date},
        dataType:'json',
        success:function(resp){
            if(resp.status == 200){
                $('#detail_schedule').modal('show');
                $('#data').html(resp.output);
            }else{
                $('#detail_schedule').modal('hide');
                error('Peringatan',resp.msg);

            }
        }
    })
})