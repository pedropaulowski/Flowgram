var menu = document.getElementById('menu')
var menu_drop = document.getElementById('dropdown-menu')
menu.onclick = function(){
    if(menu_drop.style.display == 'block')
        menu_drop.style.display = 'none'
    else 
        menu_drop.style.display = 'block'
} 

