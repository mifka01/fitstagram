<?php

namespace app\components;

/**
 * Modified ReCaptcha3 class to refresh ReCaptcha's generated hash just before it expires.
 */
class ReCaptcha3 extends \himiklab\yii2\recaptcha\ReCaptcha3
{
    public function run(): void
    {
        $view = $this->view;

        $arguments = \http_build_query([
            'render' => $this->siteKey,
        ]);

        $view->registerJsFile(
            $this->jsApiUrl . '?' . $arguments,
            ['position' => $view::POS_END]
        );
        $view->registerJs(
            <<<JS
                "use strict";
                function getReCaptcha(){
                    grecaptcha.ready(function() {
                        grecaptcha.execute("{$this->siteKey}", {action: "{$this->action}"}).then(function(token) {
                            jQuery("#" + "{$this->getReCaptchaId()}").val(token);

                            const jsCallback = "{$this->jsCallback}";
                            if (jsCallback) {
                                eval("(" + jsCallback + ")(token)");
                            }
                        });
                    });
                }
                
                getReCaptcha();
                setInterval(getReCaptcha, 110000);
                JS,
            $view::POS_READY
        );

        $this->customFieldPrepare();
    }
}
