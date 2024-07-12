$(document).ready(function(){
    
    $('input').attr('autocomplete','off');

});

function search_for_card(){
        
    const searchQuery = $("#search_for_card").val();
    const productDesignDivs = document.querySelectorAll(".card");
    let count = 0;

    for (let i = 0; i < productDesignDivs.length; i++) {

        const productDesignDiv = productDesignDivs[i];

        const title = productDesignDiv.querySelector(".card-header-title").textContent;
        if (title.toLowerCase().includes(searchQuery.toLowerCase())) {
            productDesignDiv.style.display = "block";
        } else {
            productDesignDiv.style.display = "none";
        }

    }

    productDesignDivs.forEach(div => {
        const style = window.getComputedStyle(div);
        if (style.display == 'none') {
            count++;
        }
    });
    
}
