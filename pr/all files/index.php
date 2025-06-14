<?php
require_once 'DatabaseRepository.php';
require_once 'Validator.php';
require_once 'template_helpers.php';

session_start();

// Обработка выхода
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}

// Проверяем, есть ли данные для редактирования
$isEditMode = false;
$userData = [];

if (!empty($_SESSION['user'])) {
    $isEditMode = true;
    $db = new DatabaseRepository();
    $user = $db->getUserByLogin($_SESSION['user']['login']);
    
    if ($user) {
        $userData = [
            'full_name' => $user['full_name'],
            'phone' => $user['phone'],
            'email' => $user['email'],
            'birth_date' => $user['birth_date'],
            'gender' => $user['gender'],
            'biography' => $user['biography'],
            'contract_agreed' => $user['contract_agreed'],
            'languages' => $db->getUserLanguages($user['id'])
        ];
    }
}

// Добавляем в начало файла обработку POST-запроса без JavaScript
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nojs'])) {
    require_once 'DatabaseRepository.php';
    require_once 'Validator.php';
    
    $data = [
        'full_name' => $_POST['full_name'],
        'phone' => $_POST['phone'],
        'email' => $_POST['email'],
        'birth_date' => $_POST['birth_date'],
        'gender' => $_POST['gender'],
        'languages' => $_POST['languages'] ?? [],
        'biography' => $_POST['biography'],
        'contract_agreed' => isset($_POST['contract_agreed'])
    ];
    
    $errors = Validator::validateUserForm($data);
    
    if (empty($errors)) {
        $db = new DatabaseRepository();
        
        if (!empty($_SESSION['user'])) {
            // Редактирование существующего пользователя
            $user = $db->getUserByLogin($_SESSION['user']['login']);
            if ($user && $_POST['password']) {
                if (md5($_POST['password']) === $user['pass']) {
                    $db->updateUser($user['id'], $data);
                    $_SESSION['message'] = 'Данные успешно обновлены';
                } else {
                    $_SESSION['errors']['general'] = 'Неверный пароль';
                }
            }
        } else {
            // Создание нового пользователя
            $result = $db->createUser($data);
            $_SESSION['message'] = 'Пользователь создан. Логин: ' . $result['login'] . 
                                  ', Пароль: ' . $result['pass'];
        }
    } else {
        $_SESSION['errors'] = $errors;
        $_SESSION['values'] = $_POST;
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
  <head>
    <title>Заголовок</title>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script> 
    <script src="./footer/script_footer.js" defer></script> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./footer/style_footer.css" media="screen">
    <link rel="stylesheet" href="./p/style.css" media="screen">
    <link rel="stylesheet" href="./tariffs/tariffStyle.css">
    <script src="./tariffs/tariff.js" defer></script>
    <link rel="stylesheet" href="./FAQ/Style/style.css" media="screen">
    <script src="./FAQ/Style/accordion.js" defer></script>
    <link rel="stylesheet" type="text/css" href="./caise/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <link rel="stylesheet" href="./otzivi/styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <link rel="stylesheet" href="./shapka/style-menu.css">
    <script src="./shapka/button.js"></script>
    <link rel="stylesheet" href="./exp/style.css">
    <link rel="stylesheet" href="./friendi/style.css">
    <link rel="stylesheet" href="./style.css">
     <!-- стили для формы -->
    <link rel="stylesheet" href="form_styles.css">
  </head>

  <body>

    <header>
      <video src="./shapka/menu.mp4" autoplay muted loop></video>
      <div class="cap px-md-5">
        <nav class="navbar">
          <input type="checkbox" name="menu" id="menu">
          <ul class="m">
            <li>
              <a class="drupal-support-menu" href="./ErrorPage/index.html" id="msup"> ПОДДЕРЖКА DRUPAL </a>
            </li>
            <li id="admin">
              <a id="admin1" href="./ErrorPage/index.html"> АДМИНИСТРИРОВАНИЕ &blacktriangledown;</a>
              <ul class="navbar2">
                <li><a href="./ErrorPage/index.html"> МИГРАЦИЯ </a></li>
                <li><a href="./ErrorPage/index.html"> БЭКАПЫ </a></li>
                <li><a href="./ErrorPage/index.html"> АУДИТ БЕЗОПАСНОСТИ </a></li>
                <li><a href="./ErrorPage/index.html"> ОПТИМИЗАЦИЯ СКОРОСТИ </a></li>
                <li><a href="./ErrorPage/index.html"> ПЕРЕЕЗД НА HTTPS </a></li>
              </ul>
            </li>
            <li>
              <a href="./ErrorPage/index.html" id="m"> ПРОДВИЖЕНИЕ </a>
            </li>
            <li>
              <a href="./ErrorPage/index.html" id="m1"> РЕКЛАМА </a>
            </li>
            <li id="about_us1">
              <a href="./ErrorPage/index.html" id="about_us2"> О НАС &blacktriangledown;</a>
              <ul class="navbar3">
                <li><a href="./ErrorPage/index.html"> КОМАНДА </a></li>
                <li><a href="./ErrorPage/index.html"> DRUPALDIVE </a></li>
                <li><a href="./ErrorPage/index.html"> БЛОГ </a></li>
                <li><a href="./ErrorPage/index.html"> КУРСЫ DRUPAL </a></li>
              </ul>
            </li>
            <li>
              <a href="./ErrorPage/index.html" id="m2"> ПРОЕКТЫ </a>
            </li>
            <li>
              <a href="./ErrorPage/index.html" id="m3"> КОНТАКТЫ </a>
            </li>
          </ul>
          <div class="logo-menu">
            <img id="logo" src="./shapka/drupal-coder.svg" alt="logo" width="140" height="20">
            <label for="menu" id="menu1"><img src="./shapka/menu-triple.png" width="25" height="20" alt=""></label>
          </div>
        </nav>
      </div>
      <div class="header-center px-md-5">
        <div class="pup">
          <h1> Поддержка сайтов на Drupal </h1>
          <p> Сопровождение и поддержка сайтов на CMS Drupal любых версий запущенности </p>
          <button onclick="scrollToSection('block-tariffs')"> Тарифы </button>
        </div>
        <div class="ad">
          <div class="first_trio">
            <div class="advert1 col-xl-4">
              <h1> #1 </h1>
              <img src="./shapka/cup.png" alt="cup" width="59" height="58">
              <p> Drupal-разработчик по России по версии Рейтинга Рунета </p>
            </div>
            <div class="advert col-xl-4">
              <h1> 3+ </h1>
              <p> средний опыт специалистов более 3 лет</p>
            </div>
            <div class="advert col-xl-4">
              <h1> 14 </h1>
              <p> лет опыта в сфере Drupal </p>
            </div>
          </div>
          <div class="second_trio">
            <div class="advert col-xl-4">
              <h1> 50+ </h1>
              <p> модулей и тем в формате DripalGive </p>
            </div>
            <div class="advert col-xl-4">
              <h1> 90000+ </h1>
              <p> часов поддержки сайтов на Drupal </p>
            </div>
            <div class="advert col-xl-4">
              <h1> 300+ </h1>
              <p> Проектов на поддержке </p>
            </div>
          </div>
        </div>
      </div>
    </header>


    <div class="container">
      <div class="namer" style="margin-top: 7%;">13 лет совершенствуем <br> компетенции в Друпал <br> поддержке!</div>

    <div class="coment">
      Разрабатываем и оптимизируем модули, расширяем <br> функциональность сайтов, обновляем дизайн
    </div>

    <div class="row row-flex competencies-row">
      <div class="col-sm-3 col-xs-6">
        <div class="competency"><img src="p/competency-1.svg"><img id="i" src="p/icon.svg"></div>
          <div class="coment">
            Добавление <br> информации на сайт,<br> создание новых <br> разделов
          </div>
        </div>
      <div class="col-sm-3 col-xs-6">
        <div class="competency"><img src="p/competency-2.svg"><img id="i" src="p/icon.svg"></div>
        <div class="coment">
            Разработка <br> и оптимизация<br> модулей сайта
        </div>
      </div>
      <div class="col-sm-3 col-xs-6">
        <div class="competency"><img src="p/competency-3.svg"><img id="i" src="p/icon.svg"></div>
        <div class="coment">
          Интеграция с CRM, <br> 1С, платежными <br> системами, любыми <br> веб-сервисами
        </div>
      </div>
      <div class="col-sm-3 col-xs-6">
        <div class="competency"><img src="p/competency-4.svg"><img id="i" src="p/icon.svg"></div>
        <div class="coment">
          Любые доработки <br> функционала<br> и дизайна 
        </div>
      </div>
      <div class="col-sm-3 col-xs-6">
        <div class="competency"><img src="p/competency-5.svg"><img id="i" src="p/icon.svg"></div>
        <div class="coment">
          Аудит имониторинг<br> безопасности Drupal <br> сайтов
        </div>
      </div> 
      <div class="col-sm-3 col-xs-6">
        <div class="competency"><img src="p/competency-6.svg"><img id="i" src="p/icon.svg"></div>
        <div class="coment">
          Миграция, импорт <br> контента и апрейд<br> Drupal
        </div>
      </div>
      <div class="col-sm-3 col-xs-6">
        <div class="competency"><img src="p/competency-7.svg"><img id="i" src="p/icon.svg"></div>  
        <div class="coment">
          Оптимизация <br> и ускорение <br> Drupal-сайтов
        </div>
      </div>
      <div class="col-sm-3 col-xs-6">
        <div class="competency"><img src="p/competency-8.svg"><img id="i" src="p/icon.svg"></div>
        <div class="coment">
          Веб-маркетинг <br> консультации <br> и работы по SEO
        </div>
      </div>
    </div>
      
  </div>
       
      
      <div class="container">
        <div class="my">
          <div  class="namer" id="h">Поддержка<br>от Drupal-coder</div>
          <div class="row row-flex advantages-row">
            <div class="col-12 col-md-6 col-lg-3 advantage-col">
              <div class="advantage">
                <div class="advantage-wrapper">
                  <div class="advantage-num">01.</div>
                  <div class="advantage-title">Постановка задачи по Email</div>
                  <div class="coment">
                    <h2>Удобная и привычная модель постановки задач, при которой задачи фиксируются и никогда не теряются.</h2>
                  </div>
                </div>
                <div class="advantage-icon"><img style = "position: absolute; z-index:-1; right: 0px; bottom: 0px;" src="p/support1.svg"></div>               
              </div> 
            </div>

            <div class="col-12 col-md-6 col-lg-3 advantage-col">
              <div class="advantage">
                <div class="advantage-wrapper">
                  <div class="advantage-num">02.</div>
                  <div class="advantage-title">Система Hilpdesk - отчётность, прозрачность</div>
                  <div class="coment">
                    <h2>Возможность посмотреть все заявки в работе и отработанные часы в личном кабинете через браузер.</h2>
                  </div>
                </div>
                <div class="advantage-icon"><img style = "position: absolute; z-index:-1; right: 0px; bottom: 0px;" src="p/support2.svg"></div>               
              </div> 
            </div>

            <div class="col-12 col-md-6 col-lg-3 advantage-col">
              <div class="advantage">
                <div class="advantage-wrapper">
                  <div class="advantage-num">03.</div>
                  <div class="advantage-title">Расширенная техническая поддержка</div>
                  <div class="coment">
                    <h2>Возможность организации расширенной техподдержки с 6:00 до 22:00 без выходных.</h2>
                  </div>
                </div>
                <div class="advantage-icon"><img style = "position: absolute; z-index:-1; right: 0px; bottom: 0px;" src="p/support3.svg"></div>               
              </div> 
            </div>

            <div class="col-12 col-md-6 col-lg-3 advantage-col">
              <div class="advantage">
                <div class="advantage-wrapper">
                  <div class="advantage-num">04.</div>
                  <div class="advantage-title">Персональный менеджер проекта</div>
                  <div class="coment">
                    <h2>Ваш менеджер проекта всегда в курсе текущего состояния проекта и в любой момент готов ответить на любые вопросы.</h2>
                  </div>
                </div>
                <div class="advantage-icon"><img style = "position: absolute; z-index:-1; right: 0px; bottom: 0px;" src="p/support4.svg"></div>               
              </div> 
            </div>
            
          
            <div class="col-12 col-md-6 col-lg-3 advantage-col">
              <div class="advantage">
                <div class="advantage-wrapper">
                  <div class="advantage-num">05.</div>
                  <div class="advantage-title">Удобные способы оплаты</div>
                  <div class="coment">
                    <h2>Безналичный расчёт или электронные деньги: WebMoney, Яндекс.Деньги, Paypal.</h2>
                  </div>
                </div>
                <div class="advantage-icon"><img style = "position: absolute; z-index:-1; right: 0px; bottom: 0px;" src="p/support5.svg"></div>               
              </div> 
            </div>

            <div class="col-12 col-md-6 col-lg-3 advantage-col">
              <div class="advantage">
                <div class="advantage-wrapper">
                  <div class="advantage-num">06.</div>
                  <div class="advantage-title">Работаем с SLA и NDA</div>
                  <div class="coment">
                    <h2>Работа в рамках соглашений о конфинденциальности и обуровне качества работ.</h2>
                  </div>
                </div>
                <div class="advantage-icon"><img style = "position: absolute; z-index:-1; right: 0px; bottom: 0px;" src="p/support6.svg"></div>               
              </div> 
            </div>

            <div class="col-12 col-md-6 col-lg-3 advantage-col">
              <div class="advantage">
                <div class="advantage-wrapper">
                  <div class="advantage-num">07.</div>
                  <div class="advantage-title">Штатные специалисты</div>
                  <div class="coment">
                    <h2>Надёжные штатные специалисты, никаких фрилансеров.</h2>
                  </div>
                </div>
                <div class="advantage-icon"><img style = "position: absolute; z-index:-1; right: 0px; bottom: 0px;" src="p/support7.svg"></div>               
              </div> 
            </div>

            <div class="col-12 col-md-6 col-lg-3 advantage-col">
              <div class="advantage">
                <div class="advantage-wrapper">
                  <div class="advantage-num">08.</div>
                  <div class="advantage-title">Удобные каналы связи</div>
                  <div class="coment">
                    <h2>Консультации по телефону, скайпу, в месенджерах. </h2>
                  </div>
                </div>
                <div class="advantage-icon"><img style = "position: absolute; z-index:-1; right: 0px; bottom: 0px;" src="p/support8.svg"></div>               
              </div> 
            </div>

          </div>   
        </div>
      </div>


        <div class="drupal">
          <img src="./exp/drupal-icon-2.svg" style="position: absolute; z-index: 2; right: 0; height: 93%;">
          <div class="container0">
            <div class="row">
              <div class="col-md-12 col-lg-12 col-xs-12 col-md-offset-6">
                
                <div class="row">
                  <h3 class="col-12"> Экспертиза в Drupal, <br> опыт 14 лет! </h3>
                </div>
      
                <div class="row row-flex">
      
                  <div class="drupal-3 col-sm-6 col-xs-12">
                    <div class="drupal-hide">
                      <p> Только системный подход – контроль версий, резервирование и тестирование! </p>
                    </div>
                  </div>
      
                  <div class="drupal-3 col-sm-6 col-xs-12">
                    <div class="drupal-hide">
                      <p> Только Drupal сайты, не берем на поддержку сайты на других CMS! </p>
                    </div>
                  </div>
      
                  <div class="drupal-3 col-sm-6 col-xs-12">
                    <div class="drupal-hide">
                      <p> Участвуем в разработке ядра Drupal и модулей на Drupal.org, разрабатываем <a href="./ErrorPage/index.html"> свои модули Drupal </a></p>
                    </div>
                  </div>
      
                  <div class="drupal-3 col-sm-6 col-xs-12">
                    <div class="drupal-hide">
                      <p> Поддерживаем сайты на Drupal 5, 6, 7 и 8 </p>
                    </div>
                  </div>
      
                </div>
              </div>
            </div>
          </div>
      
          <div class="side-laptop">
            <img alt="side-laptop" src="./exp/laptop.png">
          </div>
        </div>

        <section id="block-tariffs">
          <h2 class="block-title">Тарифы</h2>
          <div class="tariffs-row">
              <div class="tariff-wrapper">
                  <div class="tariff-header">
                      <h3 class="tariff-title">Стартовый</h3>
                  </div>
                  <div class="tariff-body">
                      <div class="tarrif-body-item">Консультация и работы по SEO</div>
                      <div class="tarrif-body-item">Услуги дизайнера</div>
                      <div class="tarrif-body-item">Неиспользованные оплачиваемые часы переносятся на следующий месяц</div>
                      <div class="tarrif-body-item">Предоплата от 6 000 рублей в месяц</div>
                  </div>
                  <div class="tariff-footer">
                      <a href="./ErrorPage/index.html" class="contact-form tariff-footer-btn">СВЯЖИТЕСЬ С НАМИ!</a>
                  </div>
              </div>
              <div class="tariff-wrapper">
                  <div class="tariff-header">
                      <h3 class="tariff-title">Бизнес</h3>
                  </div>
                  <div class="tariff-body">
                      <div class="tarrif-body-item">Консультация и работы по SEO</div>
                      <div class="tarrif-body-item">Услуги дизайнера</div>
                      <div class="tarrif-body-item">Высокое время реакции - до 2 рабочих дней</div>
                      <div class="tarrif-body-item">Неиспользованные оплачиваемые часы не переносятся</div>
                      <div class="tarrif-body-item">Предоплата от 30 000 рублей в месяц</div>
                  </div>
                  <div class="tariff-footer">
                      <a href="./ErrorPage/index.html" class="contact-form tariff-footer-btn">СВЯЖИТЕСЬ С НАМИ!</a>
                  </div>
              </div>
              <div class="tariff-wrapper">
                  <div class="tariff-header">
                      <h3 class="tariff-title">VIP</h3>
                  </div>
                  <div class="tariff-body">
                      <div class="tarrif-body-item">Консультация и работы по SEO</div>
                      <div class="tarrif-body-item">Услуги дизайнера</div>
                      <div class="tarrif-body-item">Максимальное время реакции - в день обращения</div>
                      <div class="tarrif-body-item">Неиспользованные оплачиваемые часы не переносятся</div>
                      <div class="tarrif-body-item">Предоплата от 270 000 рублей в месяц</div>
                  </div>
                  <div class="tariff-footer">
                      <a href="./ErrorPage/index.html" class="contact-form tariff-footer-btn">СВЯЖИТЕСЬ С НАМИ!</a>
                  </div>
              </div>
          </div>
          <div class="tariffs-ps">
              Вам не подходят наши тарифы? Оставьте заявку и мы
  предложим вам индивидуальные условия!
              <a href="./ErrorPage/index.html" class="tariffs-link">ПОЛУЧИТЬ ИНДИВИДУАЛЬНЫЙ ТАРИФ</a>
          </div>
      </section>

      <div class="container">
        <div  class="namer">Наши профессиональные разработчики выполняют быстро любые задачи</div>

        <div class="row row-flex competencies-row" id="n0">
          
          <div class="col-sm-4 col-xs-12" >
            <div class="competency" id="c1"><img src="./p/competency-20.svg"><img id="i1" src="p/icon.svg"></div>
              <div class="namer"  style="margin-top: 10px; margin-bottom: 0;">от 1ч</div>
              <div class="coment" style="margin-top: 5px ;" >
                Настройка события GA<br>в интернет-магазине
              </div>
            </div>
          <div class="col-sm-4 col-xs-12">
            <div class="competency"><img src="./p/competency-21.svg"><img id="i1" src="p/icon.svg"></div>
            <div class="namer" style="margin-top: 10px; margin-bottom: 0;">от 20ч</div>
            <div class="coment" style="margin-top: 5px ;">
              Разработка мобильной<br>версии сайта
            </div>
          </div>
          <div class="col-sm-4 col-xs-12">
            <div class="competency" ><img src="./p/competency-22.svg"><img id="i1" src="p/icon.svg"></div>
            <div class="namer" style="margin-top: 10px; margin-bottom: 0;">от 8ч</div>
            <div class="coment" style="margin-top: 5px ;">
              Интеграция <br> модуля оплаты
            </div>
          </div>
        </div>

        <div class="my">
          <div  class="namer" id="h">Команда</div>

          <section class="team">
            <div class="container">
              <div class="team-unit">
                <div class="row">
                  
                  <div class="col-6 col-sm-6 col-md-4 members-unit">
                    <div class="members-panel">
                      <div class="members-picture">
                        <img src="p/IMG1.jpg" alt="Фото члена команды" width="280" height="280"
                          class="pic-adaptive" />
                      </div>
                      <div class="members-name">Сергей Синица</div>
                      <div class="members-function">Руководитель отдела веб-разработки, канд.техн. наук, заместитель
                        директора</div>
                    </div>
                  </div>
                  <div class="col-6 col-sm-6 col-md-4 members-unit">
                    <div class="members-panel">
                      <div class="members-picture">
                        
                        <img src="p/IMG2.jpg" alt="Фото члена команды" width="280" height="280"
                          class="pic-adaptive" />
                        
                      </div>
                      <div class="members-name">Роман Агабеков</div>
                      <div class="members-function">Руководитель отдела DevOPS, директор</div>
                    </div>
                  </div>
                  <div class="col-6 col-sm-6 col-md-4 members-unit">
                    <div class="members-panel">
                      <div class="members-picture">
                        <img src="p/IMG3.jpg" alt="Фото члена команды" width="280" height="280"
                          class="pic-adaptive" />
                      </div>
                      <div class="members-name">Алексей Синица</div>
                      <div class="members-function">Руководитель отдела поддержки сайтов</div>
                    </div>
                  </div>
                  <div class="col-6 col-sm-6 col-md-4 members-unit">
                    <div class="members-panel">
                      <div class="members-picture">
                        <img src="p/IMG4.jpg" alt="Фото члена команды" width="280" height="280"
                          class="pic-adaptive" />
                      </div>
                      <div class="members-name">Дарья Бочкарёва</div>
                      <div class="members-function">Руководитель отдела продвижения, контекстной рекламы и контент-поддержки
                        сайтов</div>
                    </div>
                  </div>
                  <div class="col-6 col-sm-6 col-md-4 members-unit">
                    <div class="members-panel">
                      <div class="members-picture">
                        <img src="p/IMG5.jpg" alt="Фото члена команды" width="280" height="280"
                          class="pic-adaptive" />
                      </div>
                      <div class="members-name">Ирина Торкунова</div>
                      <div class="members-function">Менеджер по работе с клиентами</div>
                    </div>
                  </div>
                </div>
                <div class="all-team"><a href="./ErrorPage/index.html">ВСЯ КОМАНДА</a></div>
              </div>
            </div>
          </section>
        </div>

        <section class="container last-cases" style="margin-top: 100px;">
          <h2>
            Последние кейсы
          </h2>
  
          <div class="last-cases-table">
            <a class="case-block" href="./ErrorPage/index.html">
              <div class="case-pic" style="background-image: url(./caise/img/1.jpg);"></div>
              <h3 class="for_h3">
                Настройка кэширования данных. Апгрейд сервера. Ускорение работы сайта в 30 раз!
              </h3>
              <div class="case-post-date">04.05.2020</div>
              <div class="case-text">Влияние скорости загрузки страниц сайта на отказы и конверсии. Кейс ускорения...
              </div>
            </a>
            <a class="case-block fullscreen-case" href="./ErrorPage/index.html">
              <div class="case-pic" style="background-image: url(./caise/img/4.jpg)"></div>
              <h3 class="for_h3">Использование отчетов Ecommerce в Яндекс.Метрике</h3>
            </a>
            <a class="case-block fullscreen-case" href="./ErrorPage/index.html">
              <div class="case-pic" style="background-image: url(./caise/img/3.jpg)"></div>
              <h3 class="for_h3">
                Повышение конверсии страницы с формой заявки с применением AB-тестирования
              </h3>
              <div class="case-post-date">24.01.2020</div>
            </a>
            <a class="case-block fullscreen-case" href="./ErrorPage/index.html">
              <div class="case-pic" style="background-image: url(./caise/img/speed_case.jpg)"></div>
              <h3 class="for_h3">
                Drupal 7: ускорение времени генерации страниц интернет-магазина на 32%
              </h3>
              <div class="case-post-date">23.09.2019</div>
            </a>
            <a class="case-block" href="./ErrorPage/index.html">
              <div class="case-pic" style="background-image: url(./caise/img/monitor_case.png)"></div>
              <h3 class="for_h3">
                Обмен товарами и заказами интернет-магазинов на Drupal 7 с 1C: Предприятие, МойСклад, Класс365
              </h3>
              <div class="case-post-date">22.08.2019</div>
              <div class="case-text">Опубликован <span class="case-href"> релиз модуля...</span></div>
            </a>
          </div>
        </section>
      </div>

        <script src="./otzivi/slides.js"></script>
        <section id="testimonials">
            <img src="./otzivi/quote.svg" style="position: absolute; z-index: -1; ">
            <h2 class="block-title">Отзывы</h2>
    
            <div class="form-group">
                <div class="content">
                    <div class="slick" data-blazy="">
                        <div id="slick-slider" class="slick-slider">
    
                            <div class="slick-slide">
                                <div class="logo">
                                    <img loading="lazy" src="./otzivi/logos/logo_0.png" width="78" height="46" alt="Ciel parfum">
                                </div>
                                <div class="report-text">
                                    <div class="field-content">Долгие поиски единственного и неповторимого мастера на многострадальный
                                        сайт www.cielparfum.com, который был собран крайне некомпетентным программистом и раз в месяц
                                        стабильно грозил погибнуть, привели меня на сайт и, в итоге, к ребятам из Drupal-coder. И вот уже
                                        практически полгода как не проходит и дня, чтобы я не поудивлялась и не порадовалась своему
                                        везению!
                                        Починили все, что не работало - от поиска до отображения меню. Провели редизайн - не отходя от
                                        желаемого, но со своими существенными и качественными дополнениями. Осуществили ряд проектов -
                                        конкурсы, тесты и тд. А уж мелких починок и доработок - не счесть! И главное - все качественно и
                                        быстро (не взирая на не самый "быстрый" тариф). Есть вопросы - замечательный Алексей всегда
                                        подскажет,
                                        поддержит, отремонтирует и/или просто сделает с нуля. Есть задумка для реализации - замечательный
                                        Сергей обсудит и предложит идеальный вариант. Есть проблема - замечательные Надежда и Роман
                                        починят,
                                        поправят, сделают! Ребята доказали, что эта CMS - мощная и грамотная система управления. Надеюсь,
                                        что
                                        наше сотрудничество затянется надолго! Спасибо!!!</div>
                                </div>
                                <div class="report-author">
                                    <div class="field-content">С уважением, Наталья Сушкова руководитель Отдела веб-<br>
                                        проектов Группы компаний "Си Эль<br>
                                        парфюм"&nbsp;<a href="http://www.cielparfum.com/">http://www.cielparfum.com/</a>
                                    </div>
                                </div>
                            </div>
    
                            <div class="slick-slide">
                                <div class="logo">
                                    <img loading="lazy" src="./otzivi/logos/logo.png" width="113" height="46" alt="personal-writer.com">
                                </div>
                                <div class="report-text">
                                    <div class="field-content">Сергей — профессиональный, высококвалифицированый программист с огромным
                                        опытом в ИТ. Я долгое время общался с топ-фрилансерами (вся первая двадцатка) на веблансере и могу
                                        сказать, что С СЕРГЕЕМ ОНИ И РЯДОМ НЕ ВАЛЯЛИСЬ. Работаем с Сергеем до сих пор. С ним приятно
                                        работать,
                                        я доволен.</div>
                                </div>
                                <div class="report-author">
                                    <div class="field-content">Сергей Чепурко, руководитель проектов&nbsp;<a
                                            href="http://www.personal-writer.com/">www.personal-writer.com</a>&nbsp;/&nbsp;<a
                                            href="http://www.writers-united.org/">www.writers-united.org</a></div>
                                </div>
                            </div>
    
                            <div class="slick-slide">
                                <div class="logo">
                                    <img loading="lazy" src="./otzivi/logos/farbors_ru.jpg" width="192" height="46" alt="farbors.ru">
                                </div>
                                <div class="report-text">
                                    <div class="field-content">Выражаю глубочайшую благодарность команде специалистов компании
                                        "Инитлаб"
                                        и
                                        лично Дмитрию Купянскому и Алексею Синице. Сайт был первоклассно перевёрстан из старой табличной
                                        модели в новую на базе Drupal с дополнительной функциональностью. Всё было сделано с высочайшим
                                        качеством и точно в сроки. Всем кому хочется без нервов и лишних вопросов разработать сайт
                                        рекомендую
                                        обращаться именно к этой команде профессионалов.</div>
                                </div>
                                <div class="report-author">
                                    <div class="field-content">Леонид Александрович Ледовский</div>
                                </div>
                            </div>
    
                            <div class="slick-slide">
                                <div class="logo">
                                    <img loading="lazy" src="./otzivi/logos/nashagazeta_ch.png" width="157" height="46"
                                        alt="nashagazeta.ch">
                                </div>
                                <div class="report-text">
                                    <div class="field-content">Моя электронная газета www.nashagazeta.ch существует в Швейцарии уже 10
                                        лет.
                                        За это время мы сменили 7 специалистов по техподдержке, и только сейчас, в последние несколько
                                        месяцев, с начала сотрудничества с Алексеем Синицей и его командой, я впервые почувствовала, что у
                                        меня есть надежный технический тыл. Без громких слов и обещаний, ребята просто спокойно и
                                        качественно
                                        делают работу, быстро реагируют, находят решения, предлагают конкретные варианты улучшения
                                        функционирования сайта и сами их оперативно осуществляют. Сотрудничество с ними – одно
                                        удовольствие!</div>
                                </div>
                                <div class="report-author">
                                    <div class="field-content">Надежда Сикорская, Женева, Швейцария</div>
                                </div>
                            </div>
    
                            <div class="slick-slide">
                                <div class="logo">
                                    <img loading="lazy" src="./otzivi/logos/logo-estee.png" width="99" height="46" alt="estee-design.ru">
                                </div>
                                <div class="report-text">
                                    <div class="field-content">Наша компания Estee Design занимается дизайном интерьеров. Сайт сверстан
                                        на
                                        Drupal. Искали программистов под выполнение ряда небольших изменений и корректировок по сайту.
                                        Пообщавшись с Алексеем Синицей, приняли решение о начале работ с компанией Initlab/drupal-coder.
                                        Сотрудничеством довольны на 100%. Четкая и понятная система коммуникации, достаточно оперативное
                                        решение по задачам. Дали рекомендации по улучшению програмной части сайта, исправили ряд скрытых
                                        ошибок.
                                        Никогда не пишу отзывы (нет времени), но в данном случае, по просьбе Алексея, не могу не
                                        рекомендовать
                                        Initlab другим людям - действительно компания профессионалов.</div>
                                </div>
                                <div class="report-author">
                                    <div class="field-content">Кузин Вадим, руководитель строительного направления Дизайн-бюро
                                        интерьеров&nbsp;<a href="http://estee-design.ru">estee-design.ru</a>
                                    </div>
                                </div>
                            </div>
    
                            <div class="slick-slide">
                                <div class="logo">
                                    <img loading="lazy" src="./otzivi/logos/cableman_ru.png" width="200" height="41" alt="cableman.ru">
                                </div>
                                <div class="report-text">
                                    <div class="field-content">Наша компания за несколько лет сменила несколько команд программистов и
                                        специалистов техподдержки, и почти отчаялась найти на российском рынке адекватное профессиональное
                                        предложение по разумной цене. Пока мы не начали работать с командой "Инитлаб", воплощающей в себе
                                        все
                                        наши представления о нормальной системе взаимодействия в сочетании с профессиональным
                                        неравнодушием.
                                        Впервые в моей деловой практике я чувствую надежно прикрытыми важнейшие задачи в жизни
                                        электронного
                                        СМИ, при том, что мои коллеги работают за сотни километров от нас и мы никогда не встречались
                                        лично.</div>
                                </div>
                                <div class="report-author">
                                    <div class="field-content">Константин Бельский, зам. генерального директора портала
                                        <a href="http://www.cableman.ru/">Кабельщик.ру</a>
                                    </div>
                                </div>
                            </div>
    
                            <div class="slick-slide">
                                <div class="logo">
                                    <img loading="lazy" src="./otzivi/logos/logo_2.png" width="165" height="46" alt="www.serebro.ag">
                                </div>
                                <div class="report-text">
                                    <div class="field-content">За довольно продолжительный срок (2014 - 2016 годы) весьма плотной
                                        работы
                                        (интернет-магазин на безумно замороченном Drupal 6: устраняли косяки разработчиков, ускоряли сайт,
                                        сделали множество новых функций и т.п.) - только самые добрые эмоции от работы с командой Initlab
                                        /
                                        Drupal-coder: всегда можно рассчитывать на быструю и толковую помощь, поддержку, совет. Даже
                                        сейчас,
                                        не смотря на то, что мы почти год не работали на постоянной основе (банально закончились задачи),
                                        случайно возникшая проблема с сайтом была решена мгновенно! В общем, только самые искренние
                                        благодарности и рекомендации!
                                        Спасибо!</div>
                                </div>
                                <div class="report-author">
                                    <div class="field-content">С уважением, Владислав,&nbsp;<a
                                            href="http://serebro.ag">Serebro.Ag</a></div>
                                </div>
                            </div>
    
                            <div class="slick-slide">
                                <div class="logo">
                                    <img loading="lazy" src="./otzivi/logos/lpcma_rus_v4.jpg" width="110" height="46"
                                        alt="http://lpcma.tsu.ru/en">
                                </div>
                                <div class="report-text">
                                    <div class="field-content">Хотел поблагодарить за работу над нашими сайтами.
                                        За 4 месяца работы привели сайт в порядок, а самое главное получили инструмент, с помощью которого мы
                                        теперь можем быстро и красиво выставлять контент для образования и работы с предприятиями
                                        Ну и многому научись благодаря работе с вами. Мы очень рады, что удалось найти настолько
                                        компетентных
                                        ребят</div>
                                </div>
                                <div class="report-author">
                                    <div class="field-content">Дмитрий Новиков,&nbsp;<a
                                            href="http://lpcma.tsu.ru">lpcma.tsu.ru</a></div>
                                </div>
                            </div>
    
                        </div>
                        <nav class="arrow">
                            <div class="arrow-wrapper">
                                <button type="button" class="slick-prev" aria-label="Предыдущий">&lt;</button>
                                <span class="slide-num"></span>
                                <button type="button" class="slick-next" aria-label="Следующий">&gt;</button>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </section>

<div class="working">
    <h3> С нами работают </h3>
    <h2> Десятки компаний доверяют нам самое ценное, что у них есть в интернете – свои<br>
      сайты. Мы делаем всё, чтобы наше сотрудничество было долгим. </h2>
    <div class="carousel">
      <div class="group">
        <div class="card"><img src="./friendi/logo-2-1.png" alt="logo"></div>
        <div class="card"><img src="./friendi/farbors_ru.jpg" alt="logo"></div>
        <div class="card"><img src="./friendi/logo-2-3.png" alt="logo"></div>
        <div class="card"><img src="./friendi/logo_0.png" alt="logo"></div>
      </div>
      <div aria-hidden class="group">
        <div class="card"><img src="./friendi/cableman_ru.png" alt="logo"></div>
        <div class="card"><img src="./friendi/logo-2-2.png" alt="logo"></div>
        <div class="card"><img src="./friendi/logo.png" alt="logo"></div>
        <div class="card"><img src="./friendi/logo-2-4.png" alt="logo"></div>
      </div>
    </div>
    <div class="carousel">
      <div class="group2">
        <div class="card"><img src="./friendi/logo-2-2.png" alt="logo"></div>
        <div class="card"><img src="./friendi/logo-2-4.png" alt="logo"></div>
        <div class="card"><img src="./friendi/lpcma_rus_v4.jpg" alt="logo"></div>
        <div class="card"><img src="./friendi/nashagazeta_ch.png" alt="logo"></div>
      </div>
      <div aria-hidden class="group2">
        <div class="card"><img src="./friendi/logo-estee.png" alt="logo"></div>
        <div class="card"><img src="./friendi/logo-2-3.png" alt="logo"></div>
        <div class="card"><img src="./friendi/logo_2.png" alt="logo"></div>
        <div class="card"><img src="./friendi/logo-2-1.png" alt="logo"></div>
      </div>
    </div>
  </div>

      <div class="container">
        <div class="FAQ">
          <h3> FAQ </h3>
          <div class="accordion">
          <div class="accordion-item">
              <div class="accordion-header"> <div class="accordion-num p-1 mb-1 mt-1"> 1.</div> <h3 class="p-1 mb-1 mt-1" id="p-2"> Кто непосредственно занимается поддержкой?</h3></div>
              <div class="accordion-content">
                  <p>Сайты поддерживают штатные сотрудники ООО «Инитлаб», г.Краснодар, прошедшие специальное обучение и имеющие опыт работы с Друпал от 4 до 15 лет: 8 web-разработчиков, 2 специалиста по SEO, 4 системных администратора.</p>
              </div>
          </div>
  
          <div class="accordion-item">
              <div class="accordion-header"> <div class="accordion-num p-1 mb-1 mt-1"> 2.</div> <h3 class="p-1 mb-1 mt-1" id="p-2"> Как организована работа поддержки? </h3></div>
              <div class="accordion-content">
                  <p>Сайты поддерживают штатные сотрудники ООО «Инитлаб», г.Краснодар, прошедшие специальное обучение и имеющие опыт работы с Друпал от 4 до 15 лет: 8 web-разработчиков, 2 специалиста по SEO, 4 системных администратора.</p>
              </div>
          </div>
  
          <div class="accordion-item">
              <div class="accordion-header"> <div class="accordion-num p-1 mb-1 mt-1"> 3.</div> <h3 class="p-1 mb-1 mt-1" id="p-2"> Что происходит, когда отработаны все предоплаченные часы за месяц? </h3></div>
              <div class="accordion-content">
                  <p>Сайты поддерживают штатные сотрудники ООО «Инитлаб», г.Краснодар, прошедшие специальное обучение и имеющие опыт работы с Друпал от 4 до 15 лет: 8 web-разработчиков, 2 специалиста по SEO, 4 системных администратора.</p>
              </div>
          </div>
  
          <div class="accordion-item">
              <div class="accordion-header"> <div class="accordion-num p-1 mb-1 mt-1"> 4.</div> <h3 class="p-1 mb-1 mt-1" id="p-2"> Что происходит, когда не отработаны все предоплаченные часы за месяц? </h3></div>
              <div class="accordion-content">
                  <p>Сайты поддерживают штатные сотрудники ООО «Инитлаб», г.Краснодар, прошедшие специальное обучение и имеющие опыт работы с Друпал от 4 до 15 лет: 8 web-разработчиков, 2 специалиста по SEO, 4 системных администратора.</p>
              </div>
          </div>
  
          <div class="accordion-item">
              <div class="accordion-header"> <div class="accordion-num p-1 mb-1 mt-1"> 5.</div> <h3 class="p-1 mb-1 mt-1" id="p-2"> Как происходит оценка и согласование планируемого времени на выполнение заявок? </h3></div>
              <div class="accordion-content">
                  <p>Сайты поддерживают штатные сотрудники ООО «Инитлаб», г.Краснодар, прошедшие специальное обучение и имеющие опыт работы с Друпал от 4 до 15 лет: 8 web-разработчиков, 2 специалиста по SEO, 4 системных администратора.</p>
              </div>
          </div>
  
          <div class="accordion-item">
              <div class="accordion-header"> <div class="accordion-num p-1 mb-1 mt-1"> 6.</div> <h3 class="p-1 mb-1 mt-1" id="p-2"> Сколько программистов выделяется на проект? </h3></div>
              <div class="accordion-content">
                  <p>Сайты поддерживают штатные сотрудники ООО «Инитлаб», г.Краснодар, прошедшие специальное обучение и имеющие опыт работы с Друпал от 4 до 15 лет: 8 web-разработчиков, 2 специалиста по SEO, 4 системных администратора.</p>
              </div>
          </div>
  
          <div class="accordion-item">
              <div class="accordion-header"> <div class="accordion-num p-1 mb-1 mt-1"> 7.</div> <h3 class="p-1 mb-1 mt-1" id="p-2"> Как подать заявку на внесение изменений на сайте? </h3></div>
              <div class="accordion-content">
                  <p>Сайты поддерживают штатные сотрудники ООО «Инитлаб», г.Краснодар, прошедшие специальное обучение и имеющие опыт работы с Друпал от 4 до 15 лет: 8 web-разработчиков, 2 специалиста по SEO, 4 системных администратора.</p>
              </div>
          </div>
  
          <div class="accordion-item">
              <div class="accordion-header"> <div class="accordion-num p-1 mb-1 mt-1"> 8.</div> <h3 class="p-1 mb-1 mt-1" id="p-2"> Как подать заявку на добавление пользователя, изменение настроек веб-сервера и других задач по администрированию? </h3></div>
              <div class="accordion-content">
                  <p>Сайты поддерживают штатные сотрудники ООО «Инитлаб», г.Краснодар, прошедшие специальное обучение и имеющие опыт работы с Друпал от 4 до 15 лет: 8 web-разработчиков, 2 специалиста по SEO, 4 системных администратора.</p>
              </div>
          </div>
  
          <div class="accordion-item">
              <div class="accordion-header"> <div class="accordion-num p-1 mb-1 mt-1"> 9.</div> <h3 class="p-1 mb-1 mt-1" id="p-2"> В течение какого времени начинается работа по заявке? </h3></div>
              <div class="accordion-content">
                  <p>Сайты поддерживают штатные сотрудники ООО «Инитлаб», г.Краснодар, прошедшие специальное обучение и имеющие опыт работы с Друпал от 4 до 15 лет: 8 web-разработчиков, 2 специалиста по SEO, 4 системных администратора.</p>
              </div>
          </div>
          
          <div class="accordion-item">
              <div class="accordion-header"> <div class="accordion-num p-1 mb-1 mt-1"> 10.</div> <h3 class="mb-1 mt-2" id="p-2"> В какое время работает поддержка? </h3></div>
              <div class="accordion-content">
                  <p>Сайты поддерживают штатные сотрудники ООО «Инитлаб», г.Краснодар, прошедшие специальное обучение и имеющие опыт работы с Друпал от 4 до 15 лет: 8 web-разработчиков, 2 специалиста по SEO, 4 системных администратора.</p>
              </div>
          </div>
  
          <div class="accordion-item">
              <div class="accordion-header"> <div class="accordion-num p-1 mb-1 mt-1"> 11.</div> <h3 class="mb-1 mt-2" id="p-2"> Подходят ли услуги поддержки, если необходимо произвести обновление ядра Drupal или модулей? </h3></div>
              <div class="accordion-content">
                  <p>Сайты поддерживают штатные сотрудники ООО «Инитлаб», г.Краснодар, прошедшие специальное обучение и имеющие опыт работы с Друпал от 4 до 15 лет: 8 web-разработчиков, 2 специалиста по SEO, 4 системных администратора.</p>
              </div>
          </div>
  
          <div class="accordion-item">
              <div class="accordion-header"> <div class="accordion-num p-1 mb-1 mt-1"> 12.</div> <h3 class="mb-1 mt-2" id="p-2"> Можно ли пообщаться со специалистом голосом или в мессенджере? </h3></div>
              <div class="accordion-content">
                  <p>Сайты поддерживают штатные сотрудники ООО «Инитлаб», г.Краснодар, прошедшие специальное обучение и имеющие опыт работы с Друпал от 4 до 15 лет: 8 web-разработчиков, 2 специалиста по SEO, 4 системных администратора.</p>
              </div>
          </div>
      </div>
    </div>
  </div>

    <footer class="footer position-relative">
    <div class="footer-background"></div>
      
    <img src="./footer/footer-D-1.svg" alt="Декоративный элемент 1" class="footer-image image-1">
    <img src="./footer/footer-D-2.png" alt="Декоративный элемент 2" class="footer-image image-2">

    <div class="container">
        <div class="footer-content position-relative">
            <div class="row">
                <div class="col-lg-6 col-md-12 mb-4">
                    <h2 class="footer-title">Оставить заявку</h2>
                    <h2 class="footer-title">на поддержку сайта</h2>
                    <p class="footer-description">
                        Срочно нужна поддержка сайта? Ваша команда не успевает справляться самостоятельно или предыдущий подрядчик не справился с работой? Тогда вам точно к нам! Просто оставьте заявку и наш менеджер с вами свяжется!
                    </p>

                    <div class="d-flex align-items-center mt-4">
                        <div class="me-3">
                            <div class="d-flex align-items-center mb-2">
                                <img src="./footer/phone_icon.png" alt="Иконка телефона" class="icon-small me-2">
                                <p class="footer-contact mb-0"><a href="tel:+78002222673" class="text-white text-decoration-none">8 800 222-26-73</a></p>
                            </div>
                            <div class="d-flex align-items-center">
                                <img src="./footer/mail_icon.png" alt="Иконка email" class="icon-small me-2">
                                <p class="footer-contact mb-0"><a href="mailto:info@drupal-coder.ru" class="text-white text-decoration-line">info@drupal-coder.ru</a></p>
                            </div>
                        </div>
                    </div>                    
                </div>

                <div class="col-lg-6 col-md-12">
<form id="application-form" method="POST" action="api.php" enctype="multipart/form-data">
          <?php if ($isEditMode): ?>
              <div class="edit-notice mb-3">
                  Режим редактирования. <a href="index.php?logout=1" class="logout-link">Выйти и создать нового пользователя</a>
              </div>
          <?php endif; ?>

          <div class="mb-2">
              <input type="text" class="form-control" name="full_name" id="full_name" placeholder="ФИО" 
                     value="<?= htmlspecialchars($userData['full_name'] ?? '') ?>" required pattern="[A-Za-zА-Яа-я\s]{1,150}" maxlength="150">
              <div class="error-message" id="full_name_error"></div>
          </div>

          <div class="mb-2">
              <input type="tel" class="form-control" name="phone" id="phone" placeholder="Телефон (+7XXXXXXXXXX)" 
                     value="<?= htmlspecialchars($userData['phone'] ?? '') ?>" required pattern="\+7\d{10}">
              <div class="error-message" id="phone_error"></div>
          </div>

          <div class="mb-2">
              <input type="email" class="form-control" name="email" id="email" placeholder="E-mail" 
                     value="<?= htmlspecialchars($userData['email'] ?? '') ?>" required>
              <div class="error-message" id="email_error"></div>
          </div>

          <div class="mb-2">
              <input type="date" class="form-control" name="birth_date" id="birth_date" placeholder="Дата рождения" 
                     value="<?= htmlspecialchars($userData['birth_date'] ?? '') ?>" required>
              <div class="error-message" id="birth_date_error"></div>
          </div>
          
          <div class="mb-2">
              <label class="text-white">Пол:</label>
              <div class="radio-group">
                  <label class="text-white me-3">
                      <input type="radio" name="gender" value="male" 
                             <?= ($userData['gender'] ?? '') === 'male' ? 'checked' : '' ?> required> Мужской
                  </label>
                  <label class="text-white">
                      <input type="radio" name="gender" value="female" 
                             <?= ($userData['gender'] ?? '') === 'female' ? 'checked' : '' ?>> Женский
                  </label>
              </div>
              <div class="error-message" id="gender_error"></div>
          </div>
          
          <div class="mb-2">
              <label class="text-white">Любимый язык программирования:</label>
              <select name="languages[]" id="languages" multiple required class="form-control">
                  <?php 
                  $db = new DatabaseRepository();
                  $allLanguages = $db->getAllLanguages();
                  $selectedLanguages = $userData['languages'] ?? [];
                  
                  foreach ($allLanguages as $lang): 
                      $selected = in_array($lang['id'], $selectedLanguages) ? 'selected' : '';
                  ?>
                      <option value="<?= $lang['id'] ?>" <?= $selected ?>>
                          <?= htmlspecialchars($lang['language_name']) ?>
                      </option>
                  <?php endforeach; ?>
              </select>
              <div class="error-message" id="languages_error"></div>
              <small class="text-white">Удерживайте Ctrl для выбора нескольких языков</small>
          </div>
          
          <div class="mb-2">
              <label for="biography" class="text-white">Биография:</label>
              <textarea class="form-control" name="biography" id="biography" required maxlength="500"><?= 
                  htmlspecialchars($userData['biography'] ?? '') 
              ?></textarea>
              <div class="error-message" id="biography_error"></div>
          </div>
          
          <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" id="contract_agreed" name="contract_agreed" 
                    <?= isset($userData['contract_agreed']) && $userData['contract_agreed'] ? 'checked' : '' ?> required>
              <label class="form-check-label text-white" for="contract_agreed">
                  Согласен с контрактом
              </label>
              <div class="error-message" id="contract_agreed_error"></div>
          </div>
          
          <button type="submit" class="btn btn-danger w-100">
              <?= $isEditMode ? 'Обновить данные' : 'Отправить' ?>
          </button>
      </form>
      <div id="form-result" class="result"></div>
                </div>
            </div>

            <hr class="footer-divider mt-5 mb-4">

            <div class="row">
                <div class="col-lg-12 d-flex align-items-center mb-2 social-icons">
                    <a href="https://vk.com" class="footer-icon me-3">
                        <img src="./footer/vk_icon.png" alt="VK" class="social-icon">
                    </a>
                    <a href="https://facebook.com" class="footer-icon me-3">
                        <img src="./footer/facebook_icon.png" alt="Facebook" class="social-icon">
                    </a>
                    <a href="https://youtube.com" class="footer-icon me-3">
                        <img src="./footer/youtube_icon.png" alt="YouTube" class="social-icon">
                    </a>
                    <a href="https://web.telegram.org" class="footer-icon me-3">
                        <img src="./footer/tg_icon.png" alt="Telegram" class="social-icon">
                    </a>
                </div>

                <div class="col-lg-12 mt-2">
                    <p class="footer-note mb-1">Проект ООО "Инитлаб", Краснодар, Россия.</p>
                    <p class="footer-note mb-1">Drupal является зарегистрированной торговой маркой Dries Buytaert.</p>
                </div>
            </div>
        </div>
    </div>
</footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="form_script.js"></script>

  </body>

</html>
 