
<script src="{{ asset('panel/backend/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('panel/assets/js/loader.js') }}"></script>
<script src="{{ asset('panel/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('panel/bootstrap/js/bootstrap-popper.min.js') }}"></script>
<script src="{{ asset('panel/bootstrap/js/bootstrap.min.js') }}"></script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js" integrity="sha512-6sSYJqDreZRZGkJ3b+YfdhB3MzmuP9R7X1QZ6g5aIXhRvR1Y/N/P47jmnkENm7YL3oqsmI6AK+V6AD99uWDnIw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('panel/backend/js/fileinput.js') }}"></script>
<script src="{{ asset('panel/assets/js/app.js') }}"></script>
<script>
  $(document).ready(function() {
      App.init();
  });
</script>
<!--<script src="{{ asset('panel/plugins/apex/apexcharts.min.js') }}"></script>-->
<script src="{{ asset('panel/assets/js/dashboard/dash_1.js') }}"></script>
<script src=" https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js "></script>
<script src="{{ asset('panel/assets/js/custom.js') }}"></script>




<script type="text/javascript">

    document.addEventListener('DOMContentLoaded', function () {
        
        
    // Función para ocultar la imagen añadiendo una clase
    function ocultarImagen() {
        const imagen = document.getElementById("myImage");
        if (imagen) {
            imagen.classList.add("hidden-img");
        } else {
            console.warn("El elemento con ID 'myImage' no existe en el DOM.");
        }
    }

    // Función para limpiar la imagen y actualizar el texto del span
    function limpiarImagen() {
        const imagen = document.getElementById('miImagen');
        const span = document.getElementById('miSpan');

        if (imagen && span) {
            imagen.src = ""; // Limpia la imagen
            span.textContent = "Agregar una imagen"; // Actualiza el texto del span
        } else {
            console.warn("El elemento con ID 'miImagen' o 'miSpan' no existe en el DOM.");
        }
    }

    // Opcional: Exponer las funciones globalmente si se usan en el HTML
    window.ocultarImagen = ocultarImagen;
    window.limpiarImagen = limpiarImagen;
 



    const fileInput = document.getElementById("myFileInput");
    const previewImage = document.getElementById("previewImage");

    if (fileInput && previewImage) {
        fileInput.addEventListener("change", function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = "block"; // Muestra la imagen
                };
                reader.readAsDataURL(file);
            } else {
                previewImage.src = "#"; // Restablece la imagen
                previewImage.style.display = "none"; // Oculta la imagen
            }
        });
    } else {
        console.warn("El elemento 'myFileInput' o 'previewImage' no existe en el DOM.");
    }


  });

</script>



@livewireScripts