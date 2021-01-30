jQuery(function($){

    // показывать html форму при нажатии кнопки «Обновить товар» 
    $(document).on('click', '.update-theme-button', function(){

        // получаем ID товара 
        var id = $(this).attr('data-id');

                // читаем одну запись на основе данного идентификатора товара 
        $.getJSON("http://diplom/api/theme/read_one.php?id=" + id, function(data){

            // значения будут использоваться для заполнения нашей формы 
            var name = data.name;
            var filepatch = data.filepatch;
            var section_id = data.section_id;
            var section_name = data.section_name;

            // загрузка списка категорий 
            $.getJSON("http://diplom/api/section/read.php", function(data){

                // строим список выбора 
                // перебор полученного списка данных 
                var sections_options_html=`<select name='section_id' class='form-control'>`;

                $.each(data.records, function(key, val){
                    // опция предварительного выбора - это идентификатор категории 
                    if (val.id==section_id) {
                        sections_options_html+=`<option value='` + val.id + `' selected>` + val.name + `</option>`;
                    } else {
                        sections_options_html+=`<option value='` + val.id + `'>` + val.name + `</option>`; 
                    }
                });
                sections_options_html+=`</select>`;

                // сохраним html в переменной «update theme» 
                var update_theme_html=`
                <div id='read-themes' class='btn btn-primary pull-right m-b-15px read-themes-button'>
                    <span class='glyphicon glyphicon-list'></span> Список всех тем
                </div>

                <!-- построение формы для изменения товара -->
                <!-- мы используем свойство 'required' html5 для предотвращения пустых полей -->
                <form id='update-theme-form' action='#' method='post' border='0'>
                    <table class='table table-hover table-responsive table-bordered'>

                        <tr>
                            <td>Название</td>
                            <td><input value=\"` + name + `\" type='text' name='name' class='form-control' required /></td>
                        </tr>

                        <tr>
                            <td>Путь к файлу</td>
                            <td><input type='file' name='file' class='form-control' value=`+filepatch+` required></td>
                        </tr>

                        <tr>
                            <td>Раздел</td>
                            <td>` + sections_options_html + `</td>
                        </tr>

                        <tr>
                            <!-- скрытый «идентификатор продукта», чтобы определить, какую запись удалить -->
                            <td><input value=\"` + id + `\" name='id' type='hidden' /></td>

                            <!-- кнопка отправки формы -->
                            <td>
                                <button type='submit' class='btn btn-info'>
                                    <span class='glyphicon glyphicon-edit'></span> Обновить тему
                                </button>
                            </td>
                        </tr>

                    </table>
                </form>
                `;

                // добавим в «page-content» нашего приложения 
                $("#page-content").html(update_theme_html);

                // изменим title страницы 
                changePageTitle("Обновление темы");
            });
        });
    });

    $(document).on('submit', '#update-theme-form', function(){
        var $input = $("#filepatch");
        var fd = new FormData();
        var form_data = $(this).serializeObject();
        fd.append("file", $input.prop("files")[0]);
        fd.append("section_id", form_data.section_id);
        fd.append("name", form_data.name);
        $.ajax({
          url: "/app/theme/update_file.php",
          data: fd,
          cache: false,
          contentType: false,
          processData: false,
          type: "POST",
          success: function (response) {
            $('#response').html(`<div class='alert alert-success'>Тема была успешно обновлена.</div>`);
            showThemesFirstPage();
          },
          error: function (xhr, resp, text) {
            $('#response').html(`<div class='alert alert-danger'>Ошибка обновления. Попробуйте еще раз.</div>`);
          },
        });
    
        return false;
      });
});