<main class="registro">
    <h2 class="registro__heading"><?php echo $titulo; ?></h2>
    <p class="registro__descripcion">Elige tu plan</p>
    <div class="paquetes__grid">
        <div class="paquete"> <!--Paquete 1 -->
            <h3 class="paquete__nombre">Pase Gratis</h3>
            <ul class="paquete__lista">
                <li class="paquete__elemento">Acceso Virtual a DevWebCamp</li>
            </ul>
            <p class="paquete__precio">$0</p>              

            <form action="/finalizar-registro/gratis" method="POST">
                <input type="submit" class="paquetes__submit" value="Inscripción Gratis">
            </form>

        </div>
        <div class="paquete"> <!--Paquete 2 -->
            <h3 class="paquete__nombre">Pase Presencial</h3>
            <ul class="paquete__lista">
                <li class="paquete__elemento">Acceso Presencial a DevWebCamp</li>
                <li class="paquete__elemento">Pase por 2 días</li>
                <li class="paquete__elemento">Acceso a talleres y Conferencias</li>
                <li class="paquete__elemento">Acceso a las grabaciones</li>
                <li class="paquete__elemento">Camisa del Evento</li>
                <li class="paquete__elemento">Comida y Bebida</li>
            </ul>
            <p class="paquete__precio">$199</p>   
            <div id="smart-button-container"> <!--Codigo de boton paypal-->
                <div style = "text-align: center;">
                    <div id="paypal-button-container"></div>
                </div>
            </div>           <!--Termina codigo de bton paypal-->
        </div>

        <div class="paquete"> <!--Paquete 3 -->
            <h3 class="paquete__nombre">Pase Virtual</h3>
            <ul class="paquete__lista">
                <li class="paquete__elemento">Acceso Virtual a DevWebCamp</li>
                <li class="paquete__elemento">Pase por 2 días</li>
                <li class="paquete__elemento">Acceso a talleres y Conferencias</li>
                <li class="paquete__elemento">Acceso a las grabaciones</li>
            </ul>
            <p class="paquete__precio">$49</p>              
            <div id="smart-button-container"> <!--Codigo de boton paypal-->
                <div style = "text-align: center;">
                    <div id="paypal-button-container-virtual"></div>
                </div>
            </div>                <!--Termina codigo de paypal-->
        </div>
    </div>
</main>


<!-- Reemplazar CLIENT_ID por tu client id proporcionado al crear la app desde el developer dashboard) -->
<script src="https://www.paypal.com/sdk/js?client-id=AQFyonSLbWHnHXmJk75S0gC-Jbp62RpreOEpwdM7YlTNNGQywwxTiK0HsyMrSry97VBqHzKmuX651n1s&enable-funding=venmo&currency=USD" data-sdk-integration-source="button-factory"></script> 
<script>
    function initPayPalButton() {
      //pase presencial description:1
      paypal.Buttons({
        style: {
          shape: 'rect',
          color: 'blue',
          layout: 'vertical',
          label: 'pay',
        },
 
        createOrder: function(data, actions) {
          return actions.order.create({
            purchase_units: [{"description":"1","amount":{"currency_code":"USD","value":199}}]
          });
        },
 
        onApprove: function(data, actions) {
          return actions.order.capture().then(function(orderData) {
            const datos = new FormData();                           //crea una peticion para enviar a RegistroController.php
            datos.append('paquete_id', orderData.purchase_units[0].description); //el tipo de paquete 
            datos.append('pago_id', orderData.purchase_units[0].payments.captures[0].id); //id de compra
            $url = '/finalizar-registro/pagar'; //endpoint de tipo post al pagar con metodo pagar en RegistroController
            fetch($url, {         //peticion
                method: 'POST', //por default es GET
                body: datos
            })
            .then(respuesta => respuesta.json()) //el fetch retorna respuesta y se aplica json a respuesta
            .then(resultado => { //json retorna el echo resultado y se imprime en consola el resultado
                // console.log(resultado); //imprime lo que está en try-catch
                if(resultado.resultado){//si retorna resultado el try que guarda en la DB
                    const url = window.location.origin + '/finalizar-registro/conferencias'
                    actions.redirect(url);
                }
            }); 
          });
        },
 
        onError: function(err) {
          console.log(err);
        }
      }).render('#paypal-button-container');

      //pase virtual description: 2
      paypal.Buttons({
        style: {
          shape: 'rect',
          color: 'blue',
          layout: 'vertical',
          label: 'pay',
        },
 
        createOrder: function(data, actions) {
          return actions.order.create({
            purchase_units: [{"description":"2","amount":{"currency_code":"USD","value":49}}]
          });
        },
 
        onApprove: function(data, actions) {
          return actions.order.capture().then(function(orderData) {
            //aqui hay que agregar código
            const datos = new FormData(); //prepara el POST
            datos.append('paquete_id', orderData.purchase_units[0].description); //el tipo de paquete 
            datos.append('pago_id', orderData.purchase_units[0].payments.captures[0].id); //id de compra
            $url = '/finalizar-registro/pagar'; //endpoint de tipo post al pagar con metodo pagar en RegistroController
            fetch($url, {
                method: 'POST', //por default es GET
                body: datos
            })
            .then(respuesta => respuesta.json()) //el fetch retorna respuesta y se aplica json a respuesta
            .then(resultado => { //json retorna el echo resultado y se imprime en consola el resultado
                // console.log(resultado); //imprime lo que está en try-catch
                if(resultado.resultado){//si retorna resultado el try que guarda en la DB
                    const url = window.location.origin + '/finalizar-registro/conferencias'
                    actions.redirect(url);
                }
            });
          });
        },
        onError: function(err) {
          console.log(err);
        }
      }).render('#paypal-button-container-virtual');

    }
 
  initPayPalButton();
</script>