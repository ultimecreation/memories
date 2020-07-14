window.addEventListener('DOMContentLoaded',()=>{
    const menuBtn = document.querySelector('#hamburger')
    const responsiveNav = document.querySelector('#main__nav nav')
    menuBtn.addEventListener('click',()=>{
        responsiveNav.style.left = responsiveNav.style.left=="0px"?responsiveNav.style.left="-300px" :responsiveNav.style.left="0px"
        
    })
})
