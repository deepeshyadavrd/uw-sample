const menu = document.querySelector(".menu");
const menuMain = menu.querySelector(".menu-main");
const goBack = menu.querySelector(".go-back");
const menuTitle = menu.querySelector(".menu__title");
const menuTrigger = document.querySelector(".mobile-menu-trigger");
const closeMenu = menu.querySelector(".mobile-menu-close");
let subMenu;
menuMain.addEventListener("click", (e) =>{
    if(!menu.classList.contains("active")){
        return;
    }
  if(e.target.closest(".menu-item-has-children")){
       const hasChildren = e.target.closest(".menu-item-has-children");
       
     showSubMenu(hasChildren);
  }
});
goBack.addEventListener("click",() =>{
     hideSubMenu();
})
menuTrigger.addEventListener("click",() =>{
     toggleMenu();
})
closeMenu.addEventListener("click",() =>{
     toggleMenu();
})
document.querySelector(".menu-overlay").addEventListener("click",() =>{
    toggleMenu();
})
function toggleMenu(){

    menu.classList.toggle("active");
    document.querySelector(".menu-overlay").classList.toggle("active");
    $('body').toggleClass('goback')
}
function showSubMenu(hasChildren){
   subMenu = hasChildren.querySelector(".sub-menu");
   subMenu.classList.add("active");
   subMenu.style.animation = "slideLeft 0.5s ease forwards";
  //  alert(hasChildren.querySelector("a").textContent);
   const menuTitle = hasChildren.querySelector("a").textContent;
   menu.querySelector(".current-menu-title").innerHTML=menuTitle;
   menu.querySelector(".mobile-menu-head").classList.add("active");
}
function  hideSubMenu(){  
   subMenu.style.animation = "slideRight 0.5s ease forwards";
   setTimeout(() =>{
      subMenu.classList.remove("active");	
   },300); 
   menu.querySelector(".current-menu-title").innerHTML='<a href="#"><img src="catalog/view/javascript/assets/image/urbanwoodlogo.png" ></a>';
   menu.querySelector(".mobile-menu-head").classList.remove("active");
}
window.onresize = function(){
    if(this.innerWidth >991){
        if(menu.classList.contains("active")){
            toggleMenu();
        }

    }
}
$(function () {
    "use strict";
    
    function uncheckBox() {
      var isChecked = $("#open-search").prop("checked");
      if (isChecked) {
        $("#open-search").prop("checked", false);
      }
    }
    
    $("body").on("click", function () {
      uncheckBox();
    });
    
    $("#open-search,label").on("click", function (e) {
      e.stopPropagation();
    });
  });
  