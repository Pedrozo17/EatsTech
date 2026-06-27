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
    fetch('crud_operaciones ', {
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
        const urlMenu = `${window.location.protocol}//${window.location.host}/modules/carrito/carritodecompras `;

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
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('chartPlatosMasVendidos');
    
    // Validamos que el canvas exista en el DOM antes de intentar renderizar
    if (ctx) {
        // Obtenemos los arreglos globales declarados previamente en el HTML
        if (typeof nombresPlatos !== 'undefined' && typeof cantidadesPlatos !== 'undefined') {
            new Chart(ctx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: nombresPlatos,
                    datasets: [{
                        label: 'Unidades Vendidas',
                        data: cantidadesPlatos,
                        backgroundColor: ['#ffbc00', '#cf9465', '#17a2b8', '#6c757d'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }
});
// ==========================================================================
// MÁSCARA DE MILES Y VALIDACIÓN DE PRECIO MÍNIMO (EATS-TECH)
// ==========================================================================
document.addEventListener("DOMContentLoaded", function () {
    const precioInput = document.getElementById("precio_producto");
    
    // Si el input no existe en la vista actual, detenemos el script para evitar errores
    if (!precioInput) return;

    const form = precioInput.closest("form");

    // FUNCIÓN PARA FORMATEAR NÚMEROS A MILES EN COLOMBIA (es-CO)
    function formatearA_Miles(valor) {
        // Elimina cualquier caracter que no sea un número
        let num = valor.replace(/\D/g, "");
        
        // Evita ceros a la izquierda (ej: "05" -> "5")
        if (num.startsWith("0")) {
            num = num.replace(/^0+/, "");
        }
        
        // Aplica el formato con puntos si hay un número válido
        return num ? new Intl.NumberFormat("es-CO").format(num) : "";
    }

    // 1. FORMATEAR VALOR INICIAL (Por si viene de "Editar Plato" desde la Base de Datos)
    if (precioInput.value) {
        precioInput.value = formatearA_Miles(precioInput.value);
    }

    // 2. ESCUCHAR EN TIEMPO REAL MIENTRAS EL USUARIO DIGITA
    precioInput.addEventListener("input", function (e) {
        e.target.value = formatearA_Miles(e.target.value);
    });

    // 3. VALIDAR EL PRECIO MÍNIMO Y LIMPIAR PUNTOS ANTES DE ENVIAR A PHP
// Busca donde pusiste el form.addEventListener("submit" ... anterior y déjalo así:
    if (form) {
    form.addEventListener("submit", function (e) {
        // 1. Validación del precio (la que ya tenías)
        const numeroLimpio = parseInt(precioInput.value.replace(/\./g, ""), 10);
        if (isNaN(numeroLimpio) || numeroLimpio <= 0) {
            e.preventDefault();
            alert("⚠️ El precio del plato debe ser mayor a $0.");
            precioInput.focus();
            return false;
        }
        precioInput.value = numeroLimpio;

        // 2. 🟢 NUEVA VALIDACIÓN DE STOCK
        const stockInput = document.getElementById("stock_producto");
        if (stockInput) {
            const valorStock = parseInt(stockInput.value, 10);
            if (isNaN(valorStock) || valorStock < 0) {
                e.preventDefault(); // Evita que se guarde en la BD
                alert("⚠️ El stock no puede ser un número negativo o estar vacío.");
                stockInput.focus();
                return false;
            }
        }
    });
    }

    
});