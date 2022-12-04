import baseUrl from "../base.js";
import basePath from "../domain.js";
import success from "../success.js";
import successOne from "../successOne.js";
import successTwo from "../successTwo.js";
import error from "../error.js";
import errorOne from "../errorOne.js";
import url from '../url.js';


var f1 = flatpickr($('.date'), {
    enableTime: true,
    dateFormat: "Y-m-d H:i",

});
var selectBox3 = new vanillaSelectBox("#product", {
    "minWidth":300,
    "placeHolder": "Choose..." ,
    "search": true,
    // "stayOpen":true
});
$(document).on('submit','#formAct',function(e){
    e.preventDefault();
    // $("#description").val($("#product-description").html());
    var formData = new FormData(this);
    formData.append('id', url(3));
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
 
    $.ajax({
        url: baseUrl+'voucher/edit', // point to server-side PHP script
        dataType: 'json',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: formData,
        type: 'post',
        success: function(resp){
          
            if (resp.status == 200) {
             
                successTwo('Success',resp.msg,resp.redirect);
                
            }else{
                
                
                error('Ooopss',resp.msg);
            }
        }
    });

})