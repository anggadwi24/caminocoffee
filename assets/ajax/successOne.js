export default function successOne(title,msg,redirect){
    Swal.fire({
        title: title,
        type :'success',
        text:msg,
        allowOutsideClick: false,
      allowEscapeKey: false
     
      }).then((isConfirmed) => {
        /* Read more about isConfirmed, isDenied below */
        if (isConfirmed) {
            window.location = redirect;
        }
      })
    }