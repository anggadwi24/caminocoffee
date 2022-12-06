export default function successOne(title,msg,redirect){
    Swal.fire({
        title: title,
        type :'success',
        text:msg,
        allowOutsideClick: false,
        allowEscapeKey: false
     
      }).then((confirm) => {
        /* Read more about isConfirmed, isDenied below */
        if (confirm) {
            window.location = redirect;
        }
      })
    }