import baseUrl from "../base.js";
import basePath from "../domain.js";
import success from "../success.js";
import successOne from "../successOne.js";
import successTwo from "../successTwo.js";
import error from "../error.js";
import errorOne from "../errorOne.js";


$(document).on('submit','#formSearch',function(e){
    e.preventDefault();
   
    var formData = new FormData(this);
                // formData.append('')
    
    
  
    $.ajax({
        type:'POST',
        url:baseUrl+'/schedule/getEmployee',
        data: formData,
        contentType: false,
        cache: false,
        processData:false,
        dataType :'json',
        beforeSend:function(){
            $('#data').html('<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>          </div>')
            $('input').attr('disabled',true);
            $('select').attr('disabled',true);
            $('button').attr('disabled',true);
            $('textarea').attr('disabled',true);



        },success:function(resp){
            
            if(resp.status == 200){
                $('#data').html(resp.output);
            }else{
                $('#data').html('');
                error('Peringatan',resp.msg);
            }
        },complete:function(){
            $('input').attr('disabled',false);
            $('select').attr('disabled',false);
            $('button').attr('disabled',false);
            $('textarea').attr('disabled',false);




        }
    })
            
   
})

$(document).on('submit','#formStore',function(e){
    e.preventDefault();
   
    var formData = new FormData(this);
                // formData.append('')
    
    
  
    $.ajax({
        type:'POST',
        url:baseUrl+'/schedule/store',
        data: formData,
        contentType: false,
        cache: false,
        processData:false,
        dataType :'json',
        beforeSend:function(){
           
            $('input').attr('disabled',true);
            $('select').attr('disabled',true);
            $('button').attr('disabled',true);
            $('textarea').attr('disabled',true);



        },success:function(resp){
            
            if(resp.status == 200){
               success('Berhasil',resp.msg);
            }else{
                error('Peringatan',resp.msg);
            }
        },complete:function(){
            $('input').attr('disabled',false);
            $('select').attr('disabled',false);
            $('button').attr('disabled',false);
            $('textarea').attr('disabled',false);




        }
    })
            
   
})