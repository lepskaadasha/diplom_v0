jQuery(function ($) {
  // показать html форму при нажатии кнопки «создать тему»
  $(document).on("click", ".create-theme-button", function () {
    // загрузка списка категорий
    $.getJSON("http://diplom/api/section/read.php", function (data) {
      // перебор возвращаемого списка данных и создание списка выбора
      var sections_options_html = `<select name='section_id' class='form-control'>`;
      $.each(data.records, function (key, val) {
        sections_options_html +=
          `<option value='` + val.id + `'>` + val.name + `</option>`;
      });
      sections_options_html += `</select>`;

      var create_theme_html =
        `
            <div id='read-themes' class='btn btn-primary pull-right m-b-15px read-themes-button'>
                <span class='glyphicon glyphicon-list'></span> Список всех тем
            </div>

            <form id='create-theme-form' method="post" border='0'>
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td>Название</td>
                        <td><input type='text' name='name' class='form-control' required></td>
                    </tr>
                    <tr>
                        <td>Файл</td>
                        <td><input type="file" id="filepatch" accept=".pdf" name="file" class='form-control' required></td>
                    </tr>
                    <tr>
                        <td>Раздел</td>
                        <td>` +
        sections_options_html +
        `</td>
                    </tr>

                    <!-- кнопка отправки формы -->
                    <tr>
                        <td></td>
                        <td>
                            <button type='submit' class='btn btn-primary'>
                                <span class='glyphicon glyphicon-plus'></span> Создать тему
                            </button>
                        </td>
                    </tr>

                </table>
            </form>`;

      // вставка html в «page-content» нашего приложения
      $("#page-content").html(create_theme_html);

      // изменяем тайтл
      changePageTitle("Создание темы");
    });
  });

  $(document).on("submit", "#create-theme-form", function () {
    var $input = $("#filepatch");
    var fd = new FormData();
    var form_data = $(this).serializeObject();
    fd.append("file", $input.prop("files")[0]);
    fd.append("section_id", form_data.section_id);
    fd.append("name", form_data.name);
    $.ajax({
      url: "/app/theme/upload_file.php",
      data: fd,
      cache: false,
      contentType: false,
      processData: false,
      type: "POST",
      success: function (response) {
        $('#response').html(`<div class='alert alert-success'>Тема была успешно добавлена.</div>`);
        showThemesFirstPage();
      },
      error: function (xhr, resp, text) {
        $('#response').html(`<div class='alert alert-danger'>Ошибка добавления. Попробуйте заново.</div>`);
      },
    });

    return false;
  });
});
