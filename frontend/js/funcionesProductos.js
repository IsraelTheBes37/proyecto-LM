function buscarProducto() {
    const input = document.getElementById("busqueda").value.toLowerCase();
    const filas = document.querySelectorAll("#tablaProductos tbody tr");

    filas.forEach(fila => {
        const texto = fila.innerText.toLowerCase();
        fila.style.display = texto.includes(input) ? "" : "none";
    });
}

function abrirModalEditar(producto) {
    const modal = document.getElementById("modalEditar");
    document.getElementById("edit_id").value = producto.id;
    document.getElementById("edit_descripcion").value = producto.descripcion;
    document.getElementById("edit_nom_imagen").value = producto.nom_imagen;
    document.getElementById("edit_precio").value = producto.precio;
    document.getElementById("edit_existencias").value = producto.existencias;
    document.getElementById("edit_modelo").value = producto.modelo;
    document.getElementById("edit_talla").value = producto.talla;
    document.getElementById("edit_genero").value = producto.genero;

    modal.style.display = "block";
}

function cerrarModalEditar() {
    document.getElementById("modalEditar").style.display = "none";
}

function guardarCambios() {
    const datos = {
        id_modelo: document.getElementById("edit_id").value,
        descripcion: document.getElementById("edit_descripcion").value,
        nom_imagen: document.getElementById("edit_nom_imagen").value,
        precio: document.getElementById("edit_precio").value,
        existencias: document.getElementById("edit_existencias").value,
        modelo: document.getElementById("edit_modelo").value,
        talla: document.getElementById("edit_talla").value,
        genero: document.getElementById("edit_genero").value
    };

    fetch("../models/editProducto.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams(datos)
    })
    .then(res => res.text())
    .then(res => {
        if (res.trim() === "ok") {
            location.reload(); // recargar para ver los cambios
        } else {
            alert("Error al editar producto.");
        }
    });
}

function eliminarProducto(id) {
    if (!confirm("¿Eliminar producto?")) return;

    fetch("../models/deleteProducto.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id=" + id
    })
    .then(res => res.text())
    .then(res => {
        if (res.trim() === "ok") {
            location.reload();
        } else {
            alert("Error al eliminar producto.");
        }
    });
}
