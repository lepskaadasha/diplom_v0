jQuery(function($){

   
    $(document).on('submit', '#search-theme-form', function(){

        // получаем ключевые слова для поиска 
        var keywords = $(this).find(":input[name='keywords']").val();

        // получаем данные из API на основе поисковых ключевых слов 
        $.getJSON("http://diplom/api/theme/search.php?s=" + keywords, function(data){

            // шаблон в themes.js 
            readThemesTemplate(data, keywords);

            // изменяем title 
            changePageTitle("Поиск темы: " + keywords);

        });

        // предотвращаем перезагрузку всей страницы 
        return false;
    });

});