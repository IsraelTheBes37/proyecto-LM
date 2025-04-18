let actual = 0;
const imagenes = document.querySelectorAll('img');
function cambiarimagen(){
    imagenes[actual].classList.remove('active');
    actual++;
    if (actual >= imagenes.length){
        actual = 0;}
    imagenes[actual].classList.add('active');
}
setInterval(cambiarimagen,5000);