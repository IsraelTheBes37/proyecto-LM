  // Revisar si hay un parámetro en la URL
  const params = new URLSearchParams(window.location.search);
  const status = params.get("status");

  if (status === "ok") {
    alert("Cliente registrado exitosamente.");
    // Limpia los parámetros de la URL sin recargar la página
    window.history.replaceState({}, document.title, window.location.pathname);
  } else if (status === "error") {
    alert("Error al registrar cliente. Intente nuevamente.");
  }