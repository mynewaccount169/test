$(document).ready(function(){
    $('#accordion').dcAccordion({
        eventType: 'click',
        autoClose: false,
        //	saveState: false,
        disableLink: false,
        showCount: true,
        speed: 'slow'
    });

    $(document).on('click', '.get_url', function(e){
        e.preventDefault();

        var href = $(this).attr("href");
        history.pushState(null, null, href);

        getContent(getParams().category,getParams().sort);
        getCategories(getParams().category);
        getBredcrumps(getParams().category);

    });

    getContent(getParams().category,getParams().sort);
    getCategories(getParams().category);
    getBredcrumps(getParams().category);

    function getParams() {
        var params = window
            .location
            .search
            .replace('?','')
            .split('&')
            .reduce(
                function(p,e){
                    var a = e.split('=');
                    p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                    return p;
                },
                {}
            );
        return params;
    }
    if(getParams().sort){
        $("#sort_by option[value="+getParams().sort+"]").attr("selected","selected");
    }else {
        $("#sort_by option[value="+''+"]").attr("selected","selected");

    }

    $(document).on('change', '#sort_by', function(e){
        e.preventDefault();
        var val = $("#sort_by").val();
        const url = new URL(document.location);
        url.searchParams.set('sort', val);
        history.pushState(null, null, url.href);
        // getContent(getParams().category,getParams().sort);
    });
});
var inProcess = false;
function getBredcrumps(arg) {
    $("#sort_by").val("");

    $.ajax({
        url: 'ajax_bredcrumps.php',
        data: {arg},
        type: 'post',
        cache: false,
        success: function(html){
            inProcess = false;
            $(".breadcrumbs123").html(html);
        }
    });
}
function getCategories(arg) {
    $("#sort_by").val("");
    $.ajax({
        url: 'ajax_category.php',
        data: {arg},
        type: 'post',
        dataType: 'JSON',
        success: function (response) {
            $("#sort_by option[value="+''+"]").attr("selected","selected");
            var tr_str = "<div>";
            var len = response.length;
            for (var i = 0; i < len; i++) {
                var id = response[i].id;
                var title = response[i].title;
                var parent = response[i].parent;
                tr_str +=
                    "<br />"+
                    "<a class='get_url' href=?category="+id+">" + title + "</a>";
            }
            tr_str += "</div>";
            $(".cat_content").html(tr_str);
        }
    });
}
function getContent(arg, sort_by) {



    $("#sort_by").val("");
    //  alert(arg);
    $.ajax({
        url: 'ajax_products.php',
        data: {arg,sort_by},
        type: 'post',
        dataType: 'JSON',
        success: function (response) {
            var tr_str = '';
            if(response.answer === 'no'){
                tr_str = "<div>" +
                    "<p>в этой категории товаров нет</p>";
                tr_str += "</div>";
            }else {

                var len = response.length;
                for (var i = 0; i < len; i++) {
                    var id = response[i].id;
                    var title = response[i].title;
                    var price = response[i].price;
                    var parent = response[i].parent;
                    var date = response[i].date;
                    tr_str +=
                        "<div class='goods-card' data-new='" + date + "' data-price='" + price + "' data-Alfavit='" + title + "'>" +
                        "<div>"+
                        "<h1>" +title+"</h1>" +
                        "</div>" +
                        "<div>" +
                        "<p>" + price + " грн</p>" +
                        "<p>" + date + "</p>" +
                        "</div>" +
                        "<div class='col text-center'>"+
                        "<a href='#' class='btn btn-lg btn-success' data-toggle='modal' id='buy' value='"+id+"' data-target='#addModal'>Купить</a>"+
                        "</div>";

                    tr_str += "</div>";
                }
            }
            $(".content").html(tr_str);
        }
    });
}

$(document).on('click', '#buy', function(e){
    e.preventDefault();
    var val = $(this).attr("value");
    $.ajax({
        url: 'get_one_product.php',
        data: {val},
        type: 'post',
        dataType: 'JSON',
        success: function (response) {
            // alert(response.title);
            $('#title').text(response.title);
            $('#price').text(response.price);
            $('#date').text(response.date);

        }
    });

});

document.addEventListener('DOMContentLoaded', function() {
    const SORT_SELECTOR =  document.getElementById('sort_by');
    SORT_SELECTOR.addEventListener('input', sortBy);
});

// массивы с данными для сортировки
//массив для типа данных, 'sort-asc',  'sort-new', 'sort-Alfavit' - value из select
var decode3 = {
    'sort-asc':  'number',
    'sort-new': 'string',
    'sort-Alfavit': 'string'
};

//массив с данными, 'sort-asc',  'sort-new', 'sort-Alfavit' - value из select
var decode5 = {
    'sort-asc': 'data-price',
    'sort-new': 'data-new',
    'sort-Alfavit': 'data-Alfavit'
};

function massiv(arg,paremetr) { //ф-нция для сравнения элементов массива из value из select
    // arg - входящий массив decode3 или decode5
    //  paremetr - value из select
    var eurocode = paremetr;
    var resultDC5 = '';
    var tmpString = '';
    for (i = 0; i < eurocode.length; i++) {
        tmpString += eurocode[i];
        for (var key in arg) {
            if (tmpString.indexOf(key) + 1) {
                resultDC5 += arg[key];
                tmpString = "";
                break;
            };
        };
    }
    return resultDC5;
}
function sortBy(event){  //запуск сортировки
    if(this.value){// выбранное value из select
        sortList(massiv(decode5,this.value),massiv(decode3,this.value));
    }
}
function sortList(sortType, dataType) { //ф-ция сортировки
    // sortType - по какому критерию сортировать
    // dataType типа данных для сортировки number/string ...
    let items = document.querySelector('.content');
    for (let i = 0; i < items.children.length - 1; i++) {
        for (let j = i; j < items.children.length; j++) {
            if(dataType == 'number') {
                if (+items.children[i].getAttribute(sortType) > +items.children[j].getAttribute(sortType)) {

                    let replacedNode = items.replaceChild(items.children[j], items.children[i]);
                    insertAfter(replacedNode, items.children[i]);
                }
            }
            if(dataType == 'string') {
                if (items.children[i].getAttribute(sortType) > items.children[j].getAttribute(sortType)) {

                    let replacedNode = items.replaceChild(items.children[j], items.children[i]);
                    insertAfter(replacedNode, items.children[i]);
                }
            }
        }
    }
}
function insertAfter(elem, refElem) {
    return refElem.parentNode.insertBefore(elem, refElem.nextSibling);
}