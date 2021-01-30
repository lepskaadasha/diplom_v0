<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ТКиОК</title>

    <link rel="shortcut icon" href="https://img.icons8.com/plasticine/100/000000/saving-book.png" type="image/png">

    <!-- bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" crossorigin="anonymous" />

    <!-- основной CSS -->
    <link href="app/assets/css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="app/assets/css/section.css">
</head>
<body>

<!-- navbar -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
  <a class="navbar-brand" href="index.php">
  <img style="width: 25px; height: 25px" src="https://img.icons8.com/plasticine/100/000000/saving-book.png" alt="">        
  ТКиОК
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
          <a class="nav-item nav-link" href="index.php" id='home'>Домашняя страница</a>
          <a class="nav-item nav-link" href="admin.php" id='admin'>Админ панель</a>
          <a class="nav-item nav-link" href="update_account.php" id='update_account'>Учетная запись</a>
          <a class="nav-item nav-link" id='logout'>Выход</a>
      </div>
  </div>
</nav>
<!-- /navbar -->


<!-- здесь будет выводиться наше приложение -->
<div class="admin-menu">
    <div class="admin-item"><a href="admin.php">Темы</a></div>
    <div class="admin-item"><a href="section.php">Разделы</a></div>
    <div class="admin-item"><a href="user.php">Пользователи</a></div>
</div>

<div id="response"></div>

<div id="app" style="display: none">
    <div class="container">
       
    </div>
</div>

<!-- jQuery -->
<script src="app/assets/js/jquery-3.5.1.min.js"></script>

<!-- jQuery & Bootstrap 4 JavaScript libraries -->
<script src="http://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<!-- для всплывающих окон -->
<script src="app/assets/js/bootbox.min.js"></script>

<script>
    $(document).ready(()=>{ 

        createMenu();
        function createMenu() {
        let html = `<div class="navbar-nav">
            <a class="nav-item nav-link" href="index.php" id='home'>Домашняя страница</a>`;

            var isAdmin = getCookie('isAdmin');
            if (isAdmin == 'true') {
                html += `<a class="nav-item nav-link" href="admin.php" id='admin'>Админ панель</a>`
            }
            html +=`<a class="nav-item nav-link" href="update_account.php" id='update_account'>Учетная запись</a>
                <a class="nav-item nav-link" href="login.php" id='logout'>Выход</a>
            </div>`
            $('.CreateMenu').html(html);
        }
    

        var jwt = getCookie('jwt'); 
        if (jwt) {
            $('#app').css('display','flex');
            getAllSections();
        } else {
            $('#response').html("<div class='alert alert-danger'>Пожалуйста, авторизуйтесь как администратор, чтобы получить доступ к странице учетной записи. <a href='login.php'>Войти</a></div>");
        }     

        $(document).on('click', '#create-section-button', ()=> { 
            var html = `
                <h1 style="display: flex; justify-content: space-between; margin-top: 10px;"><text>Создание раздела</text><button onclick="getAllSections()" class='btn btn-primary'>Вернуться назад</button></h1>
                <hr>
                <form style="margin-top: 20px" onsubmit="return false;" method="POST" action="#">
                    <label for="text">Название раздела</label>
                    <input type="text" class="form-control" name="section-name" id="section-name" />
                    <button type='submit' style="margin-top: 10px" onclick="createSection()" class='btn btn-primary'>Создать</button>
                </form>
            `
            $('.container').html(html);
        });

        $(document).on('submit', '#search-section-form', function(){

        // получаем ключевые слова для поиска 
        var keywords = $(this).find(":input[name='keywords']").val();
        // получаем данные из API на основе поисковых ключевых слов 
        $.getJSON("http://diplom/api/section/search.php?s=" + keywords, function(data){
            console.log(data);
            var html =`
                <h1>Список всех разделов</h1>
                <hr>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                <form id='search-section-form' action='#' style="padding-bottom: 10px" method='post'>
                    <div class='input-group pull-left w-30-pct'>
                        <input type='text' value='' style=" min-width: 200px;" name='keywords' value=`+keywords+` class='form-control' placeholder='Поиск раздела...' />
                    </div>
                </form> 
                <div class='btn btn-primary pull-right m-b-15px create-section-button' id='create-section-button'>
                    <span class='glyphicon glyphicon-plus'></span> Создать раздел
                </div>
                </div>
                <table id="table-sections" class='table table-bordered table-hover'>
                <tr><th class='w-75-pct'>Раздел</th></tr>`;

                $.each(data.records, function(key, val) {
                    html +=`
                    <tr>
                        <td>` + val.name + `</td>
                        <td>
                            <button class='btn btn-info m-r-10px update-section-button' onclick="updateSectionSHowForm('${val.id}|${val.name}')">
                                <span class='glyphicon glyphicon-edit'></span> Редактировать
                            </button>
                            <button class='btn btn-danger delete-section-button' onclick="deleteSection(${val.id})">
                                <span class='glyphicon glyphicon-remove'></span> Удалить
                            </button>
                        </td>
                    </tr>`;
                    
                });
                html += `</table>`;
                $('.container').html(html);

        });
        // предотвращаем перезагрузку всей страницы 
        return false;
        });

        function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' '){
                c = c.substring(1);
            }

            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
        }

        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            var expires = "expires="+ d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        $.fn.serializeObject = function(){

            var o = {};
            var a = this.serializeArray();
            $.each(a, function() {
                if (o[this.name] !== undefined) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
            };

        function clearResponse(){
            $('#response').html('');
        }

        $(document).on('click', '#logout', ()=> { 
            setCookie("jwt", "", 1);
            setCookie("isAdmin", "", 1);
            document.location = 'login.php'; 
        });

    });

    function updateSectionSHowForm(data) {

        var data = data.split('|');

        var html = `
            <h1 style="display: flex; justify-content: space-between; margin-top: 10px;"><text>Изменение раздела</text><button onclick="getAllSections()" class='btn btn-primary'>Вернуться назад</button></h1>
            <hr>
            <form style="margin-top: 20px" onsubmit="return false;" method="POST" action="#">
                <label for="text">Название раздела</label>
                <input type="text" class="form-control" name="section-name" id="section-name" value="${data[1]}" />
                <button type='submit' style="margin-top: 10px" onclick="updateSection(${data[0]})" class='btn btn-primary'>Сохранить</button>
            </form>
        `
        $('.container').html(html);
    }

    function updateSection(_id) {
        var data = $('#section-name').val();
            $.ajax({
                url: "http://diplom/api/section/update.php",
                type : "POST",
                dataType : 'json',
                data : JSON.stringify({ name : data, id : _id }),
                success : function(result) {
        
                    getAllSections();
                    $('#response').html(`<div class='alert alert-success'>Раздел был успешно обновлен.</div>`);
                },
                error: function(xhr, resp, text) {
                    $('#response').html(`<div class='alert alert-danger'>${JSON.parse(xhr['responseText'])['message']}</div>`);
                }
            });
    }

    function createSection() {
            var data = $('#section-name').val();
            $.ajax({
                url: "http://diplom/api/section/create.php",
                type : "POST",
                dataType : 'json',
                data : JSON.stringify({ name : data }),
                success : function(result) {
        
                    getAllSections();
                    $('#response').html(`<div class='alert alert-success'>Раздел был успешно создан.</div>`);
                },
                error: function(xhr, resp, text) {
                    console.log(xhr);
                    $('#response').html(`<div class='alert alert-danger'>${JSON.parse(xhr['responseText'])['message']}</div>`);
                }
            });
    }

    function deleteSection(_id) {
        bootbox.confirm({
        message: "<h4>Вы уверены?</h4>",
        buttons: {
            confirm: {
                label: '<span class="glyphicon glyphicon-ok"></span> Да',
                className: 'btn-danger'
            },
            cancel: {
                label: '<span class="glyphicon glyphicon-remove"></span> Нет',
                className: 'btn-primary'
            }
        },
        callback: function (result) {
                if (result==true) {
                    $.ajax({
                    url: "http://diplom/api/section/delete.php",
                    type : "POST",
                    dataType : 'json',
                    data : JSON.stringify({ id: _id }),
                    success : function(result) {
            
                        getAllSections();
                        $('#response').html(`<div class='alert alert-success'>Раздел был успешно удален. Все темы относящиеся к разделу так же удалены.</div>`);
                    },
                    error: function(xhr, resp, text) {
                        $('#response').html(`<div class='alert alert-danger'>${JSON.parse(xhr['responseText'])['message']}</div>`);
                    }
                });
            }
        }
    })
}

    function getAllSections() {
            $.ajax({
            url: "api/section/read.php",
            type : "GET",
            contentType : 'application/json',
            success : function(data) {
                var html =`
                <h1>Список всех разделов</h1>
                <hr>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                <form id='search-section-form' action='#' style="padding-bottom: 10px" method='post'>
                    <div class='input-group pull-left w-30-pct'>
                        <input type='text' value='' style=" min-width: 200px;" name='keywords' class='form-control' placeholder='Поиск раздела...' />
                    </div>
                </form> 
                <div class='btn btn-primary pull-right m-b-15px create-section-button' id='create-section-button'>
                    <span class='glyphicon glyphicon-plus'></span> Создать раздел
                </div>
                </div>
                <table id="table-sections" class='table table-bordered table-hover'>
                <tr><th class='w-75-pct'>Раздел</th></tr>`;

                $.each(data.records, function(key, val) {
                    html +=`
                    <tr>
                        <td>` + val.name + `</td>
                        <td>
                            <button class='btn btn-info m-r-10px update-section-button' onclick="updateSectionSHowForm('${val.id}|${val.name}')">
                                <span class='glyphicon glyphicon-edit'></span> Редактировать
                            </button>
                            <button class='btn btn-danger delete-section-button' onclick="deleteSection(${val.id})">
                                <span class='glyphicon glyphicon-remove'></span> Удалить
                            </button>
                        </td>
                    </tr>`;
                    
                });
                html += `</table>`;
                $('.container').html(html);
            }, 
            error: function(xhr, resp, text) {
            $('#response').html(`<div class='alert alert-danger'>${JSON.parse(xhr['responseText'])['message']}</div>`);
            }
        });
}
</script>

</body>
</html>