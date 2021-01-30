jQuery(function($){

    // показать список тем при первой загрузке 

    $(document).on('click', '.read-themes-button', function(){
        showThemesFirstPage();
    });

    // когда была нажата кнопка «страница» 
    $(document).on('click', '.pagination li', function(){
        // получаем json url 
        var json_url=$(this).find('a').attr('data-page');

        // покажем список тем 
        showThemes(json_url);
    });

});

function showThemesFirstPage(){
    var json_url="http://diplom/api/theme/read_paging.php";
    showThemes(json_url);
}

// функция для отображения списка тем 
function showThemes(json_url){

    // получаем список тем из API 
    $.getJSON(json_url, function(data){

        // HTML для перечисления тем
        readThemesTemplate(data, "");

        // изменим заголовок страницы 
        changePageTitle("Список всех тем");

    });
}
