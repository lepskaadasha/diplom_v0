jQuery(function ($) {
  // будет работать, если была нажата кнопка удаления
  $(document).on("click", ".delete-theme-button", function () {
    // получение ID товара
    var theme_id = $(this).attr("data-id");

    // bootbox для подтверждения во всплывающем окне
    bootbox.confirm({
      message: "<h4>Вы уверены?</h4>",
      buttons: {
        confirm: {
          label: '<span class="glyphicon glyphicon-ok"></span> Да',
          className: "btn-danger",
        },
        cancel: {
          label: '<span class="glyphicon glyphicon-remove"></span> Нет',
          className: "btn-primary",
        },
      },
      callback: function (result) {
        if (result == true) {
          // отправим запрос на удаление в API / удаленный сервер
          $.ajax({
            url: "http://diplom/api/theme/delete.php",
            type: "POST",
            dataType: "json",
            data: JSON.stringify({ id: theme_id }),
            success: function (result) {
              // покажем список всех тем
              $('#response').html(`<div class='alert alert-success'>Тема была успешно удалена.</div>`);
              showThemesFirstPage()
            },
            error: function (xhr, resp, text) {
              $('#response').html(`<div class='alert alert-danger'>Ошибка удаления. Попробуйте еще раз.</div>`);
            },
          });
        }
      },
    });
  });
});
