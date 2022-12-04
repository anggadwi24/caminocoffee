export default function successOne(title,msg,redirect){
    Swal.fire({
        title: title,
        icon :'success',
        text:msg,
        allowOutsideClick: false,
        allowEscapeKey: false
     
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            window.location = redirect;
        }
      })
    }