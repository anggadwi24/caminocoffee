export default function error(title,msg){
    Swal.fire({
        title: title,
       
        html:msg,
        allowOutsideClick: false,
  allowEscapeKey: false,
        
          
        customClass: 'swal-wide',
         icon:'warning',
        
        })  
    }