document.addEventListener("DOMContentLoaded", function(){

    const form = document.querySelector("form");

    if(form){
        form.addEventListener("submit", function(e){

            const data = document.querySelector("input[name='data']");
            
            if(data){
                let hoje = new Date().toISOString().split("T")[0];

                if(data.value < hoje){
                    alert("Não é possível agendar em datas passadas.");
                    e.preventDefault();
                }
            }
        });
    }

});