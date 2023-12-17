<?php

namespace Application\Controllers;

use Application\Helpers\AppHelper;
use Application\Main\AuthController;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class SocialMediaShare extends AuthController
{
    public function twitter(Request $request, Response $response)
    {
        $user = $this->user->getInfo();
        $time = time();
        $profileURL = AppHelper::getBaseUrl() . "/outer-profile/{$user['id']}?{$user['id']}{$time}";
        $twitter = "https://twitter.com/intent/tweet?url={$profileURL}&text=مرحبا بالجميع! سعيد بأنني انضممت إلى منصة تيلي إن لتقديم الاستشارات والجلسات في مجالات متنوعة. إذا كنت تحتاج إلى مساعدة في أي تخصص، أنا هنا لمساعدتك. احجز جلستك الآن ودعنا نبدأ رحلة تحقيق التغيير معًا";

        $view = new View();
        $view->set('Share/share', [
            'url' => $twitter,
        ]);

        $view->prepend('header', [
            'title' => 'Sharing on Twitter'
        ]);

        $view->append('footer');

        $response->set($view);
    }

    public function linkedin(Request $request, Response $response)
    {
        $user = $this->user->getInfo();
        $time = time();
        $profileURL = AppHelper::getBaseUrl() . "/outer-profile/{$user['id']}?{$user['id']}{$time}";
        $linkedin = "https://www.linkedin.com/sharing/share-offsite/?url={$profileURL}";

        $view = new View();
        $view->set('Share/share', [
            'url' => $linkedin,
        ]);

        $view->prepend('header', [
            'title' => 'Sharing on LinkedIn'
        ]);

        $view->append('footer');

        $response->set($view);
    }
}