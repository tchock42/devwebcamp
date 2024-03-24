//importa swiper
import Swiper from 'swiper'; 
import { Navigation } from 'swiper/modules'
import 'swiper/css';                        //importa los estilos swiper
import 'swiper/css/navigation';             //importa el css de navegacion
document.addEventListener('DOMContentLoaded', function(){ //espera a la carga del dom
    if(document.querySelector('.slider')){  //si existe la clase slider
        const opciones = {                  //opciones de slider
            slidesPerView: 1,
            spaceBetween: 15,
            freeMode: true,
            navigation: {                   //opciones de clases para botones
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            },
            breakpoints: {                  //querys para tamao
                768: {slidesPerView:2},
                1024: {slidesPerView:3},
                1200: {slidesPerView:4}
            }
        }
        Swiper.use([Navigation])
        new Swiper('.slider', opciones);    //crea la instancia swiper con las opciones
    }
});