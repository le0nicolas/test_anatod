//código para ingresar localidades dependiendo de que provincia se seleccione en el form de carga de clientes
$("#provincias").change(function(){
    var prov_id=$("#provincias").val();
    $("#localidades").empty();

    $.get("getLocalidades.php",{"prov_id":prov_id},function(return_data){
        if(return_data.length>0){
            $.each(return_data, function(key,value){
                $("#localidades").append("<option value='"+value.localidad_id+"'>"+value.localidad_nombre+"</option>");
                });
        }else{
            alert("No hay datos cargados para esa provincia");
        }
    }, "json");
});

/* ****************** */

//boton borrar de la tabla clientes
$('.btnBorrar').bind('click', function(e) {
  e.preventDefault();
  if(confirm("Eliminar cliente con ID: "+$(this).val())){
    $.post( "deleteCliente.php", {"clienteID":$(this).val()} );
    location.reload();
  }
});

/* ****************** */

//modal
var modal = document.getElementById("myModal");
var span = document.getElementsByClassName("close")[0];

/* ****************** */

// Boton modificar. Abre el modal cuando se hace click, paso el id del cliente al otro form (en un input hidden) y los valores para modificar
$('.btnModificar').bind('click', function(e) {
    e.preventDefault();
    modal.style.display = "block";
    $("#localidadUpdate").empty();

    //asigno al nuevo form de modificación, los datos del cliente seleccionado
    $( "#clienteID" ).val($(this).attr("data-idCliente"));
    $( "#nombreUpdate" ).val($(this).attr("data-nombreCliente"));
    $( "#dniUpdate" ).val($(this).attr("data-dniCliente"));
    $( "#provinciaUpdate" ).val($(this).attr("data-provincia_id"));
    let localidad_ID = $(this).attr("data-localidad_id");

    //busco las localidades para esa provincia y le asigno la localidad que tenía antes
    $.get("getLocalidades.php",{"prov_id":$(this).attr("data-provincia_id")},function(return_data){
      if(return_data.length>0){
          $.each(return_data, function(key,value){
            if (value.localidad_id == localidad_ID){
              $("#localidadUpdate").append("<option value='"+value.localidad_id+"'selected>"+value.localidad_nombre+"</option>");
            }else{
              $("#localidadUpdate").append("<option value='"+value.localidad_id+"'>"+value.localidad_nombre+"</option>");
            }
          });
      }else{
          alert("No hay datos cargados para esa provincia");
      }
    }, "json");

});

//busco localidades cuando el select de la provinciaUpdate cambia (form de modificación)
$("#provinciaUpdate").change(function(){
  var prov_id=$("#provinciaUpdate").val();
  $("#localidadUpdate").empty();

  //mando al backend el id de la provincia seleccionada y armo la nueva lista de localidades
  $.get("getLocalidades.php",{"prov_id":prov_id},function(return_data){
      if(return_data.length>0){
          $.each(return_data, function(key,value){
              $("#localidadUpdate").append("<option value='"+value.localidad_id+"'>"+value.localidad_nombre+"</option>");
              });
      }else{
          alert("No hay datos cargados para esa provincia");
      }
  }, "json");
});

// cierra el modal
span.onclick = function() {
  modal.style.display = "none";
}

// cuando se hace click afuera del modal, tambien lo cierra
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}