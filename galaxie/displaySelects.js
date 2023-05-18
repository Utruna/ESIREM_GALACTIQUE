//Création de bibliothèque JS
function loadGalaxie(idGalaxie){
    //charger tout les éléments HTML qui on pour class='galaxies'
    //tu selectione le style, la visibilité et tu lui attribue la valeur hidden
    // equivalent : <div class=galaxies visibility='hiden' > mon super code </div>
    document.getElementsByClassName('galaxies').style.visibility = 'hidden';
    document.getElementById('idGalaxie-'+idGalaxie).style.visibility = 'show';
}

function loadSS(idSS){
    document.getElementsByClassName('SystemeSolaire').style.visibility = 'hidden';
    document.getElementById('idSystemeSolaire-'+idGalaxie).style.visibility = 'show';

}