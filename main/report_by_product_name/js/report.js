$(document).ready(function(){
  
    $("#products").select2({
        allowClear:true
    });

    $("#btn_print").click(function(){

        const currentDate = new Date();
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth() + 1; // Adding 1 because months are zero-based (January is 0)
        const day = currentDate.getDate();
        const formattedMonth = month < 10 ? `0${month}` : `${month}`;
        const from=$("#from_date").val();
        const to=$("#to_date").val();
        header="<div class='mb-4'><b>Report By Product Name</b><br><b>From:</b> "+from+"<br><b>To:</b> "+to+"<br><b>Report Date:</b> "+year+"-"+formattedMonth+"-"+day+"</div>";

        $("#table_report").printThis({
            debug: false,               // show the iframe for debugging
            importCSS: true,            // import parent page css
            importStyle: false,         // import style tags
            printContainer: true,       // print outer container/$.selector
            loadCSS: "",                // path to additional css file - use an array [] for multiple
            pageTitle: "",              // add title to print page
            removeInline: false,        // remove inline styles from print elements
            removeInlineSelector: "*",  // custom selectors to filter inline styles. removeInline must be true
            printDelay: 0,            // variable print delay
            header: header,               // prefix to html
            footer: null,               // postfix to html
            base: false,                // preserve the BASE tag or accept a string for the URL
            formValues: true,           // preserve input/form values
            canvas: false,              // copy canvas content
            doctypeString: '',       // enter a different doctype for older markup
            removeScripts: false,       // remove script tags from print content
            copyTagClasses: false,      // copy classes from the html & body tag
            beforePrintEvent: null,     // function for printEvent in iframe
            beforePrint: null,          // function called before iframe is filled
            afterPrint: null            // function called before iframe is removed
        });
    })

    $("#btn_export").click(function(){
        table = document.getElementById("table_report");
        TableToExcel.convert(table, { 
            name: 'Report by product name.xlsx', 
        });
    })

    $("#btn_generate").click(function(){

        index_btn_generate=$("#btn_generate");
        index_required=$("#key_required").val();

        $("#alert-danger-report").hide();

        source_text_button=index_btn_generate.html();
        parent_text_button="<div class='spinner-border text-dark'></div>";

        index_btn_generate.removeAttr("disabled");
        index_btn_generate.html(source_text_button);

        $("#container_buttons").hide();
        $("#container_table").hide();
        $("#container_diagram").hide();

        $("#error_products").empty();
        $("#error_initial_date").empty();
        $("#error_final_date").empty();
        var products =$("#products").val();
        var initial_date =$("#from_date").val();
        var final_date =$("#to_date").val();
        // if(products==""){
        //   $("#error_products").append("Please select Products");
        // }else{
        //   $("#error_products").empty();
        // }
        if(initial_date==""){
            $("#error_initial_date").append(index_required);
        }else{
            $("#error_initial_date").empty();
        }
        if(final_date==""){
            $("#error_final_date").append(index_required);
        }else{
            $("#error_final_date").empty();
        }
        selectedProductsValues=[];
        if(products == ""){
            $("#products option").each(function() {
                selectedProductsValues.push($(this).val());
            });
        }

        token_index=$("#token");
        token=token_index.val();
        if(token == undefined || token == ""){
            return;
        }

        if(initial_date!="" && final_date!="" && token!=""){

            index_btn_generate.attr("disabled","disabled");
            index_btn_generate.html(parent_text_button);

            var data=new FormData();
            if(selectedProductsValues.length > 0){
              data.append("products",selectedProductsValues);
            }else{
              data.append("products",products);
            }
            
            data.append("initial_date",initial_date);
            data.append("final_date",final_date);
            data.append('token', token);

            $("#tfoot").show();

            $.ajax({
                url:'php_ajax/generate_report.php',
                type:'post',
                data:data,
                contentType:false,
                processData:false,
                success:function(output) {

                    index_btn_generate.removeAttr("disabled");
                    index_btn_generate.html(source_text_button);

                    $("#container_buttons").show();
                    $("#container_table").show();

                    var obj=JSON.parse(output);
                    if(obj.res==1){
                        $("#tbody").html(obj.format);
                        if(obj.nb_products > 0){
                            $("#sum_total").html("$"+obj.sum_total);
                            $("#nb_rows").html(obj.nb_products);
                            $("#total_qty").html(obj.total_qty);
                        }else{
                            $("#tfoot").hide();
                        }

                        const canvasContainer = document.getElementById('container_canvas');
                        canvasContainer.innerHTML="";

                        const dynamicID = generateRandomString(8);

                        const canvas = document.createElement('canvas');
                        canvas.id = "canvas_"+dynamicID;
                        canvas.height = 600;

                        canvasContainer.appendChild(canvas);
                        
                        //Fill diagram
                        new Chart(canvas.id, {
                            type: "bar",
                            data: {
                                labels: obj.Xvalues,
                                datasets: [{
                                    backgroundColor: "#007bff",
                                    data: obj.Yvalues
                                }]
                            },

                            options: {
                                "responsive": true,
                                "maintainAspectRatio": false,
                                title: {
                                    display: true,
                                    text: "Diagram By Product Name"
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                },
                                plugins: {
                                    legend: {
                                    display: false
                                    },
                                }
                            }
                        });
                        

                    }else{
                        $("#alert-danger-report").show();
                        setTimeout(function(){
                            $("#alert-danger-report").hide();
                        },3000);
                    }
                
                },
                error: function(error){
                    index_btn_generate.removeAttr("disabled");
                    index_btn_generate.html(source_text_button);
                    $("#alert-danger-report").show();
                    setTimeout(function(){
                        $("#alert-danger-report").hide();
                    },3000);
                }
            });
        }
    
    });

    $("#btn_table").click(function(){
        $("#container_table").show();
        $("#container_diagram").hide();
    });

    $("#btn_diagram").click(function(){
        $("#container_table").hide();
        $("#container_diagram").show();
    });

});

function generateRandomString(length) {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    for (let i = 0; i < length; i++) {
      result += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    return result;
}


