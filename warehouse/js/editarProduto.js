alert("a");
$(document).ready(function(){
    $('#formedtProduto').submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: '../crud/editarProduto.php',
            type: 'post',
            data:  new FormData(this),            
            cache: false,
            contentType: false,
            processData: false,
            success: function(data){
                if(data=== "cadastrado"){
                    $('#modalok').modal('show');
                }else if(data === "errocodigo"){
                    $('.errocodigo').html('Código já cadastrado.');
                }else{
                    $('#modalerro').modal('show'); 
                }
            }        
        });
    });

    $(".ok").click(function(){
        window.location.replace("../paginas/edtProduto.php");
    });
});