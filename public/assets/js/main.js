window.addEventListener('DOMContentLoaded',()=>{
    const hamburger = document.querySelector('#hamburger')
    
    hamburger.addEventListener('click',()=>{
        let responsiveNav = document.querySelector('#main__nav nav')
        
        if(responsiveNav.classList.contains("close")){
            responsiveNav.classList.remove("close");
            responsiveNav.classList.add("open"); 
        }else{
            if(responsiveNav.classList.contains("open")){
                responsiveNav.classList.remove("open");
                responsiveNav.classList.add("close")
               
            }
        }
        
        console.log(responsiveNav)
    })
})