$(document).ready(function(){

    let $ajaxStatus             = $("[name = change-ajax-status]");
    let $ajaxContact            = $("[name = change-ajax-contact]");
    let $ajaxOrdering           = $("input[name = change-ajax-ordering]");
    let $ajaxSelectArttribute   = $("[name = select_change_attr]");
    let $ajaxChangeCategoryNested = $("[name = category_id]");
    let $ajaxIsHome             = $("[name = change-ajax-isHome]");
    let $ajaxTag                = $('#tag');
    // Change Ajax Status
    $ajaxStatus.click(function(){
        let url      = $(this).data("url") ;
        let element  = $(this) ;
        callAjax(url,element,'status');
    });

    $ajaxIsHome.click(function(){
        let url      = $(this).data("url") ;
        let element  = $(this) ;
        callAjax(url,element,'status');
    });
    // Change Ajax Contact in route contact
    $ajaxContact.click(function(){
        let url      = $(this).data("url") ;
        let element  = $(this) ;
        callAjax(url,element,'contact');
    });
    // Change Ordering 
    $ajaxOrdering.on("change" ,function(){
        let url      = $(this).data("url").replace("new_value",$(this).val()) ;
        let element  = $(this) ;
        callAjax(url,element,'ordering');
    });
    // Change SelectBox
    $ajaxSelectArttribute.on("change" ,function(){
        let url   = $(this).data("url").replace("new_value",$(this).val()) ;
        let element  = $(this) ;
        callAjax(url,element,'select');
    });

    $ajaxChangeCategoryNested.on("change" ,function(){
        let url   = $(this).data("url").replace("new_value",$(this).val()) ;
        let element  = $(this) ;
        callAjax(url,element,'select');
    });
    
    //AUTOCOMPLETE TAG
    $ajaxTag.autocomplete({
        source:'autocomplete',
    })
    
    function callAjax(url,element,type){
        $.ajax({
            url : url ,
            type: "GET",
            dataType: "json",
            success: function (result) {
                if(result){
                    switch(type){
                        case 'status' :
                            element.text(result.name) ;
                            element.removeClass();
                            element.addClass(result.class) ;
                            element.data('url',result.link); //
                            notify(element, result.message);
                        case 'contact' :
                            element.text(result.name) ;
                            element.removeClass();
                            element.addClass(result.class) ;
                            element.data('url',result.link); //
                            notify(element, result.message);
                        case 'ordering' :
                            notify(element, result.message);
                        case 'select' :
                            notify(element, result.message);
                        case 'coupon' :
                            $("#value").find('option').remove();
                            $.each(result,function(key, value)
                            {
                                $("#value").append('<option value=' + key + '>' + value + '</option>') ; 
                            });

                    }
                }else{
                    console.log(result);
                }    
            }
        });
    }
    function notify(element,message){
        $(element).notify(
           ''+message+'' , {className: "success", position: "top", autoHideDelay: 1000, }
        );
    }
    //AUTOCOMPLETE TAG
    $ajaxTag.autocomplete({
        source:'autocomplete',
    })
    
}) ;

//ADMIN - PRODUCT - ATTRIBUTE
function showName(url){
    let id = $('#attribute-group').val();
    url = url.replace('attribute_new', id); // 
    $.get(url, function(data) { 
       $('.new').remove();
       $.each(data, function(index, value) {
          let name = value ;
          $('.x_content_attribute').after('<div class="form-group new" id="attr"><label  class="control-label col-md-3 col-sm-3 col-xs-12"> '+ value +'</label><div class="col-md-6 col-sm-6 col-xs-12"><input class="form-control col-md-6 col-xs-12  input-tags-attr" name="attribute['+name+'][]" type="text" ></div></div>');
          $('.input-tags-attr').tagsInput();
        });
   }, 'json');
}
