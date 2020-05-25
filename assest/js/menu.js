window.onload = function () {
  $('#load').fadeOut();
}


var btnMenu = document.getElementById('btn-menu');
var nav = document.getElementById('nav');
var flecha = document.getElementById('flecha');
var ver = document.getElementById('ver');
var vista_d = document.getElementById('vista_d');
var div_sombra = document.getElementById('div_sombra');
var imagen = document.getElementById('imagen');
var todo_foto = document.getElementById('todo_foto');

btnMenu.addEventListener('click', function () {
  nav.classList.toggle('mostrar');
  flecha.classList.toggle('ocultar');
});
function cambio(id) {
  if (confirm('¿Estas seguro de cancelar la solicitud?')) {
    window.location.href = 'perfil.php?del=' + id;
  }
} 
function datos(id) {
  window.location.href = 'datos_solicitud.php?dat=' + id;
}
function pregunta(id) {
  if (confirm('¿Estas seguro de eliminar esta solicitud?')) {
    window.location.href = 'admin.php?eli=' + id;
  }
}
function edit(id) {
  window.location.href = 'editar_datos.php?edi=' + id;
}
function realizar(id) {
  if (confirm('¿Esta seguro de dar por finalizado el servicio?')) {
    window.location.href = 'tecnico.php?rea=' + id;
  }
}
function detalles(id) {
  window.location.href = 'detalles.php?dat=' + id;
}
function eliminar() {
  if (confirm('¿Esta seguro de eliminar la foto?')) {
    window.location.href = 'eliminar_foto.php';
  }
}
function delete_user(id) {
  if (confirm('¿Esta seguro de eliminar este usuario?')) {
    window.location.href = 'usuarios.php?dele=' + id;
  }
}
function edit_user(id) {
  window.location.href = 'detalles_user.php?idu=' + id;
}
function foto(id) {
  window.location.href = 'foto_tecnico.php?df=' + id;
}
let inputfile = document.getElementById('file-input');
let namefile = document.getElementById('file-name');

inputfile.addEventListener('change', function (event) {
  let uploadfilename = event.target.files[0].name;
  namefile.textContent = uploadfilename;
})


