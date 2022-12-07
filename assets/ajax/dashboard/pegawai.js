import baseUrl from "../base.js";
import basePath from "../domain.js";
import success from "../success.js";
import successOne from "../successOne.js";
import successTwo from "../successTwo.js";
import error from "../error.js";
import errorOne from "../errorOne.js";

var defaultEvents = [];
$.ajax({
    type:'POST',
    url:baseUrl+'/main/getCalendarPegawai',
    dataType:'json',
    beforeSend:function(){
       
    },
    success:function(resp){
        if(resp.arr.length > 0){
            
            for(let i = 0;i<resp.arr.length;i++){
                if(resp.arr[i].end === null){
                    
                    defaultEvents.push(
                        {
                            id:resp.arr[i].id,
                            title:resp.arr[i].title,
                            start: new Date(resp.arr[i].start),
                            allDay : true,
                            className:resp.arr[i].className,


                        }
                    );
                }else{
                    defaultEvents.push(
                        {
                            id:resp.arr[i].id,
                            title:resp.arr[i].title,
                            start: new Date(resp.arr[i].start),
                            end: new Date(resp.arr[i].end),
                            allDay: false,
                            className:resp.arr[i].className,


                        }
                    );
                }
             
              
               
            }
            console.log(defaultEvents);
            $('#calendar').fullCalendar('removeEvents');
            $('#calendar').fullCalendar('addEventSource', defaultEvents);         
            $('#calendar').fullCalendar('rerenderEvents' );
        }
       
    },complete:function(){
      
    }
})

