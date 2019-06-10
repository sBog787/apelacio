<?php
///**
// * TODO:
// * 3) отправка писем
// * 1) ПЕРЕИМЕНОВАТЬ ПЛАГИН & readme
// * 2) ajax
// * 4) скрывать уведомления
// * 5) добавить текст соглашения
// * 6) добавить алерт о том, что обращение/ответ отправлены
// *
// * баги:
// * 1) модалка выводится не для всех уведомлений
// * 2) не отображается кириллица
// * 3) подставлять значения из обращений в модалку с ответом
// */
//
//include 'php/models/TicketService.php';
//include 'php/views/helpers/NotificationsHelper.php';
//
//add_action('admin_notices', 'showNotifications');
//
//add_action('admin_head', 'applyStyles');
//
//add_action('wp_footer', 'showAppealForm');
//
//add_action('wp_head', 'applyStyles');
//
//add_action('wp_head', 'addJQuery');
//
//add_action('wp_head', 'addAjax');
//
//function showNotifications(): void
//{
//    $tickets = ( new TicketService() )->findUncheckedTickets();
//    if (! empty($tickets)) {
//        $helper          = new NotificationsHelper($tickets);
//        $pathToView      = __DIR__ . '/html/answer.html';
//        $pathToScriptlet = __DIR__ . '/js/answerScript.js';
//        if (file_exists($pathToView) && file_exists($pathToScriptlet)) {
//            $html = file_get_contents($pathToView);
//            $helper->viewNotificationList();
//            $html .= '<script>';
//            $html .= file_get_contents($pathToScriptlet);
//            $html .= '</script>';
//            echo $html;
//        }
//    }
//}
//
//function addJQuery(): void
//{
//    echo '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js""></script>';
//}
//
//function addAjax(): void
//{
//    echo '<script src="wp-content/plugins/simple-tickets/js/ajax.js"></script>';
//}
//
//function applyStyles(): void
//{
//    $pathToStyles = __DIR__ . '/css/style.css';
//    $html         = '';
//    if (file_exists($pathToStyles)) {
//        $html .= '<style>';
//        $html .= file_get_contents($pathToStyles);
//        $html .= '</style>';
//    }
//    echo $html;
//}
//
//function showAppealForm(): void
//{
//    $pathToView      = __DIR__ . '/html/form.html';
//    $pathToScriptlet = __DIR__ . '/js/formScript.js';
//    $html            = '';
//    if (file_exists($pathToView) && file_exists($pathToScriptlet)) {
//        $html = file_get_contents($pathToView);
//        $html .= '<script>';
//        $html .= file_get_contents($pathToScriptlet);
//        $html .= '</script>';
////
////        //todo remove
//        $html .= '<script>';
//        $html .= file_get_contents('ajax.js');
//        $html .= '</script>';
//    }
//    echo $html;
//}
