window.addEventListener('DOMContentLoaded',()=>{
    const menuBtn = document.querySelector('#hamburger')
    const responsiveNav = document.querySelector('#main__nav nav')
    const body = document.querySelector('body')
   
    menuBtn.addEventListener('click',()=>{
        responsiveNav.style.left = responsiveNav.style.left=="0px"?responsiveNav.style.left="-300px" :responsiveNav.style.left="0px" 
    })

    if(
        window.location.href.startsWith("https://mymemories.frameworks.software/blog/article/")
        || window.location.href.startsWith("http://localhost/acmeblog/blog/article/")
        ){
        const stars = document.querySelector('.ratings').children
        for(let i=0;i<stars.length;i++){
            stars[i].addEventListener('click',()=>{
            //console.log(stars[i].value)
            
                for(let j=0; j<stars.length ;j++){
                    stars[j].classList.remove('fa-star')
                    stars[j].classList.add('fa-star-o')
                    
                }
                for(let j=0; j<=i ;j++){
                    stars[j].classList.remove('fa-star-o')
                    stars[j].classList.add('fa-star')
                    
                }
            })
        }
    }  
 
  
})
