<!doctype html>
<html lang="ru">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
 
        <title>ТКиОК</title>

        <link rel="shortcut icon" href="https://img.icons8.com/plasticine/100/000000/saving-book.png" type="image/png">

        <!-- Bootstrap 4 CSS and custom CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" crossorigin="anonymous" />
        <link rel="stylesheet" href="app/assets/css/custom.css" />
 
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
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup" class="CreateMenu">
      <div class="navbar-nav">
          <a class="nav-item nav-link" href="index.php" id='home'>Домашняя страница</a>
          <a class="nav-item nav-link" href="update_account.php" id='update_account'>Учетная запись</a>
          <a class="nav-item nav-link" id='logout'>Выход</a>
      </div>
  </div>
</nav>
<!-- /navbar -->
<div id="response" style="padding-top: 10px"></div>
<!-- container -->
<main role="main" class="container starter-template">
    <div class="col" id="sidebar">
        
    </div>
      <div class="col">

          <!-- здесь будут подсказки / быстрые сообщения -->

          <!-- здесь появится основной контент -->
          <div id="content">

          </div>
      </div>
  </div>

</main>
<!-- /container -->
 
<!-- jQuery & Bootstrap 4 JavaScript libraries -->
<script src="http://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script>
    $(document).ready(()=> {

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
            </div>`;
            $('#navbarNavAltMarkup').html(html);
        }

        var jwt = getCookie('jwt'); 

        $.post("api/validate_token.php", JSON.stringify({ jwt:jwt })).done(function(result) {

            // если валидация прошла успешно, покажем данные пользователя в форме 
            var html = `
                    <h2>Обновление аккаунта</h2>
                    <form id='update_account_form'>
                        <div class="form-group">
                            <label for="firstname">Имя</label>
                            <input type="text" class="form-control" name="firstname" id="firstname" required value="` + result.data.firstname + `" />
                        </div>

                        <div class="form-group">
                            <label for="lastname">Фамилия</label>
                            <input type="text" class="form-control" name="lastname" id="lastname" required value="` + result.data.lastname + `" />
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email" required value="` + result.data.email + `" />
                        </div>

                        <div class="form-group">
                            <label for="password">Пароль</label>
                            <input type="password" class="form-control" name="password" id="password" />
                        </div>

                        <button type='submit' class='btn btn-primary'>
                            Сохранить
                        </button>
                    </form>
                `;

            clearResponse();
            $('#content').html(html);
        })

        // в случае ошибки / сбоя сообщите пользователю, что ему необходимо войти в систему, чтобы увидеть страницу учетной записи. 
        .fail(function(result){
            $('#response').html("<div class='alert alert-danger'>Пожалуйста, авторизуйтесь, чтобы получить доступ к странице учетной записи. <a href='login.php'>Войти</a></div>");
        });

        $(document).on('click'), '#logout', ()=> { 
            setCookie("jwt", "", 1);
            setCookie("isAdmin", "", 1);
            document.location = 'login.php'; 
        }

        $(document).on('submit', '#update_account_form', function(){

        // дескриптор для update_account_form 
        var update_account_form=$(this);

        // валидация JWT для проверки доступа 
        var jwt = getCookie('jwt');

        // получаем данные формы 
        var update_account_form_obj = update_account_form.serializeObject()

        // добавим JWT 
        update_account_form_obj.jwt = jwt;

        // преобразуем значения формы в JSON с помощью функции stringify () 
        var form_data=JSON.stringify(update_account_form_obj);

        // отправить данные формы в API 
        $.ajax({
            url: "api/update_user.php",
            type : "POST",
            contentType : 'application/json',
            data : form_data,
            success : function(result) {

                // сказать, что учетная запись пользователя была обновлена 
                $('#response').html("<div class='alert alert-success'>Учетная запись обновлена.</div>");

                // сохраняем новый JWT в cookie 
                setCookie("jwt", result.jwt, 1);
            },

            // показать сообщение об ошибке пользователю 
            error: function(xhr, resp, text){
                if(xhr.responseJSON.message=="Невозможно обновить пользователя."){
                    $('#response').html("<div class='alert alert-danger'>Невозможно обновить пользователя.</div>");
                }

                else if(xhr.responseJSON.message=="Доступ закрыт."){
                    showLoginPage();
                    $('#response').html("<div class='alert alert-success'>Доступ закрыт. Пожалуйста войдите</div>");
                }
            }
        });

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
                // Удаление всех быстрых сообщений 
            function clearResponse(){
                $('#response').html('');
            }
            
        $(document).on('click', '#logout', ()=> { 
                setCookie("jwt", "", 1);
                setCookie("isAdmin", "", 1);
                document.location = 'login.php'; 
            });
    });

</script>

</body>
</html>