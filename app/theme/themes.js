
function readThemesTemplate(data, keywords){

    var read_themes_html=`
    <hr>
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <form id='search-theme-form' action='#' method='post'>
            <div class='input-group pull-left w-30-pct'>

                <input type='text' value='` + keywords + `' style=" min-width: 200px;" name='keywords' class='form-control theme-search-keywords' placeholder='Поиск темы...' />


            </div>
        </form>

        <div id='create-theme' class='btn btn-primary pull-right m-b-15px create-theme-button'>
            <span class='glyphicon glyphicon-plus'></span> Создать тему
        </div>
    </div>
        <!-- начало таблицы -->
        <table class='table table-bordered table-hover'>

            <!-- создание заголовков таблицы -->
            <tr>
                <th class='w-25-pct'>Тема</th>
                <th class='w-15-pct'>Раздел</th>
                <th class='w-25-pct text-align-center'>Путь к файлу</th>
            </tr>`;

    // перебор возвращаемого списка данных 
    $.each(data.records, function(key, val) {

        // создание новой строки таблицы для каждой записи 
        read_themes_html+=`<tr>

            <td>` + val.name + `</td>
            <td>` + val.section_name + `</td>
            <td>` + val.filepatch + `</td>

            <!-- кнопки 'действий' -->
            <td>

                <button class='btn btn-primary m-r-10px read-one-theme-button' data-id='` + val.id + `'>
                    <span class='glyphicon glyphicon-eye-open'></span> Просмотр
                </button>

                <button class='btn btn-info m-r-10px update-theme-button' data-id='` + val.id + `'>
                    <span class='glyphicon glyphicon-edit'></span> Редактировать
                </button>

                <button class='btn btn-danger delete-theme-button' data-id='` + val.id + `'>
                    <span class='glyphicon glyphicon-remove'></span> Удалить
                </button>
            </td>
        </tr>`;
    });

    // конец таблицы 
    read_themes_html+=`</table>`;

    // pagination 
    if (data.paging) {
        read_themes_html+="<ul class='pagination pull-left margin-zero padding-bottom-2em'>";

            // первая 
            if(data.paging.first!=""){
                read_themes_html+="<li style='margin: 0px 2px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 18px;'><a data-page='" + data.paging.first + "'>Первая страница</a></li>";
            }

            // перебор страниц 
            $.each(data.paging.pages, function(key, val){
                var active_page=val.current_page=="yes" ? "class='active selected'" : "";
                read_themes_html+="<li style='margin: 0px 2px; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 18px;' " + active_page + "><a data-page='" + val.url + "'>" + val.page + "</a></li>";
            });

            // последняя 
            if (data.paging.last!="") {
                read_themes_html+="<li style='margin: 0px 2px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 18px;'><a data-page='" + data.paging.last + "'>Последняя страница</a></li>";
            }
        read_themes_html+="</ul>";
    }

    // добавим в «page-content» нашего приложения 
    $("#page-content").html(read_themes_html);
}