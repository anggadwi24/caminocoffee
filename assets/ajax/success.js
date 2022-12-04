export default function success(title,msg){
    Swal.fire({
        title: title,
       
        text:msg,
        
          
        customClass: 'swal-wide',
         type:'success',
        
        })  
    }