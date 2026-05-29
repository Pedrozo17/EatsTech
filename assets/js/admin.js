    function cambiarEstadoFila(selectElement) {
    const id = selectElement.getAttribute('data-id');
    const tabla = selectElement.getAttribute('data-tabla');
    const nuevoEstado = selectElement.value;
    
    selectElement.setAttribute('data-status', nuevoEstado);

    const formData = new FormData();
    formData.append('action', 'update_status'); // <--- LE DECIMOS AL CRUD QUÉ HACER
    formData.append('id', id);
    formData.append('tabla', tabla);
    formData.append('estado', nuevoEstado);

    // Apuntamos directo a tu archivo de operaciones centralizado
    fetch('crud_operaciones.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            selectElement.style.borderColor = "var(--success)";
            setTimeout(() => { selectElement.style.borderColor = "var(--davys-grey)"; }, 600);
        } else {
            alert('❌ Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('⚙️ Hubo un problema al conectar con el servidor.');
    });
    }

    // Inicializar los colores de los selectores al cargar la página
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.status-select').forEach(select => {
            select.setAttribute('data-status', select.value);
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
    const contenedorQR = document.getElementById("contenedor-qr");

    if (contenedorQR) {
        // 1. Extraemos el slug real que PHP ya dejó escrito en el HTML
        const restauranteSlug = contenedorQR.getAttribute("data-slug");
        
        // 2. Construimos la URL limpia usando la dirección actual del servidor (sirve para localhost y Alwaysdata)
        const urlMenu = `${window.location.protocol}//${window.location.host}/modules/carrito/carritodecompras.php`;

        // 3. Imprimimos en la consola de desarrollo para verificar que la URL se arme perfecta
        console.log("URL generada para el QR:", urlMenu);

        // 4. Inicializamos y generamos el QR de forma nativa
        new QRCode(contenedorQR, {
            text: urlMenu,
            width: 180,
            height: 180,
            colorDark : "#242424",  
            colorLight : "#ffffff", 
            correctLevel : QRCode.CorrectLevel.H 
        });
    }
});