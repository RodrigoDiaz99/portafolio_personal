
<script>
    document.addEventListener('livewire:init', () => {

    window.Livewire.on('clear-imagen', () => {
        limpiarImagen();
    });
    
    window.Livewire.on('show-modal', () => {
        $('#theModal').modal('show');
    });
    

    window.Livewire.on('hide-modal', () => {
        $('#theModal').modal('hide');
        Livewire.emit('resetUI');
    });

    // Este es el evento despachado desde Livewire
    window.Livewire.on('added-close', () => {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "¡Guardado exitosamente!",
            showConfirmButton: false,
            timer: 1500
        });
        $('#theModal').modal('hide');
    });

    window.Livewire.on('added', () => {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "¡Guardado exitosamente!",
            showConfirmButton: false,
            timer: 1500
        });
    });

    window.Livewire.on('updated-close', () => {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "¡Actualización exitosa!",
            showConfirmButton: false,
            timer: 1500
        });
        $('#theModal').modal('hide');
        
    });

    window.Livewire.on('updated', () => {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "¡Actualización exitosa!",
            showConfirmButton: false,
            timer: 1500
        });
        
    });

    window.Livewire.on('deleted', () => {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "¡Eliminado con éxito!",
            showConfirmButton: false,
            timer: 1500
        });
    });

    window.Livewire.on('restore', () => {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "¡Restaurado exitosamente!",
            showConfirmButton: false,
            timer: 1500
        });
        $('#theModal').modal('hide');
    });

    window.Livewire.on('forcedelete', () => {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "¡Eliminado permanente!",
            showConfirmButton: false,
            timer: 1500
        });
    });

    window.Livewire.on('error', (message) => {
        Swal.fire({
            position: "top-end",
            icon: "error",
            title: message, // Usa el mensaje recibido como el texto del error
            showConfirmButton: false,
            timer: 1500
        });
    });



    // Eventos de actualización de contraseña de usuario
    window.Livewire.on('show-modal-pass', () => {
        $('#theModalPasswd').modal('show')
    });

    window.Livewire.on('error-password', (message) => {
        Swal.fire({
            position: "top-end",
            icon: "error",
            title: 'La contraseña actual es incorrecta.', // Usa el mensaje recibido como el texto del error
            showConfirmButton: false,
            timer: 1500
        });
    });

    window.Livewire.on('updated-password', () => {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Contraseña actualizada exitosamente.!",
            showConfirmButton: false,
            timer: 1500
        });
        $('#theModalPasswd').modal('hide');
    
    });

    window.Livewire.on('removeall', (message) => {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: message,
            showConfirmButton: false,
            timer: 1500
        });
    });

    window.Livewire.on('assigned', (message) => {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: message,
            showConfirmButton: false,
            timer: 1500
        });
    });

    window.Livewire.on('revoked', (message) => {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: message,
            showConfirmButton: false,
            timer: 1500
        });
    });

    
    window.Livewire.on('refresh-content', (message) => {
       
        $("#new-checkbox").load(location.href + " #new-checkbox>*", "");
    });

    window.Livewire.on('reply-added', ({replyId, success}) => {
        if (success) {
            Swal.fire({
                title: 'Respuesta agregada',
                showClass: {
                    popup: `
                    animate__animated
                    animate__fadeInUp
                    animate__faster
                    `
                },
                hideClass: {
                    popup: `
                    animate__animated
                    animate__fadeOutDown
                    animate__faster
                    `
                }
            });
        }
    });

    window.Livewire.on('notify', (message) => {
       Swal.fire({
        title: message,
        showClass: {
            popup: `
            animate__animated
            animate__fadeInUp
            animate__faster
            `
        },
        hideClass: {
            popup: `
            animate__animated
            animate__fadeOutDown
            animate__faster
            `
        }
        });
    });

    
    
});
</script>

@script
<script>
   $wire.on('confirmDelete',function(message){
        Swal.fire({
        title: "Estas seguro?",
        text: "",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!"
        }).then((result) => {
            if (result.isConfirmed) {  
                $wire.call("Destroy");  
            }
        });
    });

    $wire.on('confirmRestore',function(message){
        Swal.fire({
        title: "Estas seguro?",
        text: "",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Restaurar!"
        }).then((result) => {
            if (result.isConfirmed) {  
                $wire.call("Restore");  
            }
        });
    });
    $wire.on('confirmDeletePerm',function(message){
        Swal.fire({
        title: "Estas seguro?",
        text: "No podrás revertir esto.!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!"
        }).then((result) => {
            if (result.isConfirmed) {  
                $wire.call("forceDelete");  
            }
        });
    });
</script>
@endscript