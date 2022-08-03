const links = document.getElementsByTagName('a');

// Logic to highlight the current web page link
Array.from(links).forEach(element => {
    // console.log(element.href)

    if(window.location.href == element.href){
        element.classList.add('active');
    }
});
