// Funciones de validación del lado del cliente

// Validar formulario de login
function validarLogin() {
  let esValido = true

  // Limpiar mensajes de error previos
  limpiarErrores()

  const email = document.getElementById("email").value.trim()
  const password = document.getElementById("password").value

  // Validar email
  if (!email) {
    mostrarError("email-error", "El email es requerido")
    esValido = false
  } else if (!validarFormatoEmail(email)) {
    mostrarError("email-error", "Formato de email inválido")
    esValido = false
  }

  // Validar contraseña
  if (!password) {
    mostrarError("password-error", "La contraseña es requerida")
    esValido = false
  } else if (password.length < 6) {
    mostrarError("password-error", "La contraseña debe tener al menos 6 caracteres")
    esValido = false
  }

  return esValido
}

// Validar formulario de registro
function validarRegistro() {
  let esValido = true

  // Limpiar mensajes de error previos
  limpiarErroresRegistro()

  const nombre = document.getElementById("reg-nombre").value.trim()
  const email = document.getElementById("reg-email").value.trim()
  const password = document.getElementById("reg-password").value

  // Validar nombre
  if (!nombre) {
    mostrarError("nombre-error", "El nombre es requerido")
    esValido = false
  } else if (nombre.length < 2) {
    mostrarError("nombre-error", "El nombre debe tener al menos 2 caracteres")
    esValido = false
  } else if (!validarSoloLetras(nombre)) {
    mostrarError("nombre-error", "El nombre solo debe contener letras y espacios")
    esValido = false
  }

  // Validar email
  if (!email) {
    mostrarError("reg-email-error", "El email es requerido")
    esValido = false
  } else if (!validarFormatoEmail(email)) {
    mostrarError("reg-email-error", "Formato de email inválido")
    esValido = false
  }

  // Validar contraseña
  if (!password) {
    mostrarError("reg-password-error", "La contraseña es requerida")
    esValido = false
  } else if (password.length < 6) {
    mostrarError("reg-password-error", "La contraseña debe tener al menos 6 caracteres")
    esValido = false
  } else if (!validarFortalezaPassword(password)) {
    mostrarError("reg-password-error", "La contraseña debe contener al menos una letra y un número")
    esValido = false
  }

  return esValido
}

// Validar formulario de actualización
function validarActualizacion() {
  let esValido = true

  const nombre = document.getElementById("nombre").value.trim()

  // Validar nombre
  if (!nombre) {
    mostrarError("nombre-update-error", "El nombre es requerido")
    esValido = false
  } else if (nombre.length < 2) {
    mostrarError("nombre-update-error", "El nombre debe tener al menos 2 caracteres")
    esValido = false
  } else if (!validarSoloLetras(nombre)) {
    mostrarError("nombre-update-error", "El nombre solo debe contener letras y espacios")
    esValido = false
  }

  return esValido
}

// Confirmar eliminación de cuenta
function confirmarEliminacion() {
  return confirm("¿Está seguro de que desea eliminar su cuenta? Esta acción no se puede deshacer.")
}

// Funciones auxiliares de validación
function validarFormatoEmail(email) {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return regex.test(email)
}

function validarSoloLetras(texto) {
  const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/
  return regex.test(texto)
}

function validarFortalezaPassword(password) {
  const tieneLetra = /[a-zA-Z]/.test(password)
  const tieneNumero = /[0-9]/.test(password)
  return tieneLetra && tieneNumero
}

// Funciones para mostrar y limpiar errores
function mostrarError(elementoId, mensaje) {
  const elemento = document.getElementById(elementoId)
  if (elemento) {
    elemento.textContent = mensaje
    elemento.style.display = "block"
  }
}

function limpiarErrores() {
  const errores = ["email-error", "password-error"]
  errores.forEach((id) => {
    const elemento = document.getElementById(id)
    if (elemento) {
      elemento.textContent = ""
      elemento.style.display = "none"
    }
  })
}

function limpiarErroresRegistro() {
  const errores = ["nombre-error", "reg-email-error", "reg-password-error"]
  errores.forEach((id) => {
    const elemento = document.getElementById(id)
    if (elemento) {
      elemento.textContent = ""
      elemento.style.display = "none"
    }
  })
}

// Funciones para manejo de tabs
function mostrarTab(tabName) {
  // Ocultar todos los formularios
  const forms = document.querySelectorAll(".auth-form")
  forms.forEach((form) => form.classList.remove("active"))

  // Remover clase active de todos los botones
  const buttons = document.querySelectorAll(".tab-button")
  buttons.forEach((button) => button.classList.remove("active"))

  // Mostrar el formulario seleccionado
  const selectedForm = document.getElementById(tabName + "-form")
  if (selectedForm) {
    selectedForm.classList.add("active")
  }

  // Activar el botón correspondiente
  event.target.classList.add("active")

  // Limpiar errores al cambiar de tab
  limpiarErrores()
  limpiarErroresRegistro()
}

function mostrarDashboardTab(tabName) {
  // Ocultar todos los tabs
  const tabs = document.querySelectorAll(".dashboard-tab")
  tabs.forEach((tab) => tab.classList.remove("active"))

  // Remover clase active de todos los botones
  const buttons = document.querySelectorAll(".dashboard-tabs .tab-button")
  buttons.forEach((button) => button.classList.remove("active"))

  // Mostrar el tab seleccionado
  const selectedTab = document.getElementById(tabName + "-tab")
  if (selectedTab) {
    selectedTab.classList.add("active")
  }

  // Activar el botón correspondiente
  event.target.classList.add("active")
}

// Validación en tiempo real
document.addEventListener("DOMContentLoaded", () => {
  // Validación en tiempo real para email
  const emailInputs = document.querySelectorAll('input[type="email"]')
  emailInputs.forEach((input) => {
    input.addEventListener("blur", function () {
      const email = this.value.trim()
      const errorId = this.id === "email" ? "email-error" : "reg-email-error"

      if (email && !validarFormatoEmail(email)) {
        mostrarError(errorId, "Formato de email inválido")
      } else {
        const errorElement = document.getElementById(errorId)
        if (errorElement) {
          errorElement.textContent = ""
          errorElement.style.display = "none"
        }
      }
    })
  })

  // Validación en tiempo real para contraseñas
  const passwordInputs = document.querySelectorAll('input[type="password"]')
  passwordInputs.forEach((input) => {
    input.addEventListener("input", function () {
      const password = this.value
      const errorId = this.id === "password" ? "password-error" : "reg-password-error"

      if (password.length > 0 && password.length < 6) {
        mostrarError(errorId, "La contraseña debe tener al menos 6 caracteres")
      } else if (this.id === "reg-password" && password.length >= 6 && !validarFortalezaPassword(password)) {
        mostrarError(errorId, "La contraseña debe contener al menos una letra y un número")
      } else {
        const errorElement = document.getElementById(errorId)
        if (errorElement) {
          errorElement.textContent = ""
          errorElement.style.display = "none"
        }
      }
    })
  })

  // Validación en tiempo real para nombres
  const nombreInputs = document.querySelectorAll('input[name="nombre"]')
  nombreInputs.forEach((input) => {
    input.addEventListener("blur", function () {
      const nombre = this.value.trim()
      const errorId = this.id === "nombre" ? "nombre-update-error" : "nombre-error"

      if (nombre && !validarSoloLetras(nombre)) {
        mostrarError(errorId, "El nombre solo debe contener letras y espacios")
      } else {
        const errorElement = document.getElementById(errorId)
        if (errorElement) {
          errorElement.textContent = ""
          errorElement.style.display = "none"
        }
      }
    })
  })

  // Auto-ocultar mensajes después de 5 segundos
  const mensajes = document.querySelectorAll(".mensaje")
  mensajes.forEach((mensaje) => {
    setTimeout(() => {
      mensaje.style.opacity = "0"
      setTimeout(() => {
        mensaje.style.display = "none"
      }, 300)
    }, 5000)
  })
})

// Prevenir envío de formularios con Enter en campos específicos
document.addEventListener("keypress", (e) => {
  if (e.key === "Enter" && e.target.tagName === "INPUT" && e.target.type !== "submit") {
    const form = e.target.closest("form")
    if (form) {
      const submitButton = form.querySelector('button[type="submit"]')
      if (submitButton) {
        submitButton.click()
      }
    }
  }
})

// Función para mostrar/ocultar contraseñas (funcionalidad adicional)
function togglePassword(inputId) {
  const input = document.getElementById(inputId)
  const type = input.getAttribute("type") === "password" ? "text" : "password"
  input.setAttribute("type", type)
}

// Función para formatear números de teléfono
function formatearTelefono(input) {
  let valor = input.value.replace(/\D/g, "")
  if (valor.length >= 10) {
    valor = valor.substring(0, 10)
    valor = valor.replace(/(\d{3})(\d{3})(\d{4})/, "($1) $2-$3")
  }
  input.value = valor
}

// Aplicar formato de teléfono automáticamente
document.addEventListener("DOMContentLoaded", () => {
  const telefonoInputs = document.querySelectorAll('input[type="tel"]')
  telefonoInputs.forEach((input) => {
    input.addEventListener("input", function () {
      formatearTelefono(this)
    })
  })
})
