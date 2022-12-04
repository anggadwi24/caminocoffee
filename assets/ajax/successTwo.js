export default function successTwo(title,msg,redirect){
    Swal.fire({
        title: title,
        text:msg,
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Back',
        cancelButtonText: 'Stay',
        allowOutsideClick: false,
  allowEscapeKey: false

      }).then((result) => {
        if (result.isConfirmed) {
            window.location = redirect;
        }
      })
}