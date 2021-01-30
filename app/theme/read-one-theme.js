jQuery(function($){

    // обрабатываем нажатие кнопки «Просмотр товара» 
    $(document).on('click', '.read-one-theme-button', function(){
        // get theme id 
        var id = $(this).attr('data-id'); 
        // чтение записи товара на основе данного идентификатора 
        $.getJSON("http://diplom/api/theme/read_one.php?id=" + id, function(data){
            // начало html 
            var read_one_theme_html=`
            <!-- при нажатии будем отображать список тем -->
            <div id='read-themes' class='btn btn-primary pull-right m-b-15px read-themes-button'>
                <span class='glyphicon glyphicon-list'></span> Список всех тем
            </div>

            <!-- полные данные о товаре будут показаны в этой таблице -->
            <table class='table table-bordered table-hover'>

                <tr>
                    <td class='w-30-pct'>Название</td>
                    <td class='w-70-pct'>` + data.name + `</td>
                </tr>

                <tr>
                    <td>Путь к файлу</td>
                    <td>` + data.filepatch + `</td>
                </tr>

                <tr>
                    <td>Раздел</td>
                    <td>` + data.section_name + `</td>
                </tr>

            </table>`;

            // вставка html в «page-content» нашего приложения 
            $("#page-content").html(read_one_theme_html);

            // изменяем заголовок страницы 
            changePageTitle("Просмотр темы");

            
        });
    });

});