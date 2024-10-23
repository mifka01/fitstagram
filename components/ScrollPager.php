<?php

namespace app\components;

use app\assets\ScrollPagerAsset;
use yii\helpers\Html;
use yii\helpers\Json;

class ScrollPager extends \sjaakp\loadmore\LoadMorePager
{
    public function run()
    {
        $pageCount = $this->pagination->pageCount;

        if ($pageCount > 1) {
            $view = $this->view;
            ScrollPagerAsset::register($view);

            $id = $this->options['id'];

            $options = [
                'pageCount' => $pageCount,
                'pageParam' => $this->pagination->pageParam
            ];
            if ($this->indicator) {
                $options['indicator'] = $this->indicator;
            }

            $view->registerJs(<<<JS
                var lastKnownScrollPosition = 0;
                var ticking = false;
                var listenToScroll = true;
                document.documentElement.scrollTop

                function morePagerLoad(scrollPos) {
                    if (scrollPos + window.innerHeight >= document.documentElement.scrollHeight - 1000) {
                        if ($('#$id').length === 0) {
                            return;
                        }
                    $('#$id')[0].click();
                    listenToScroll = false;
                  }
                }

                document.addEventListener("scroll", (event) => {
                  lastKnownScrollPosition = window.scrollY;

                    if (!ticking && listenToScroll) {
                    window.requestAnimationFrame(() => {
                      morePagerLoad(lastKnownScrollPosition);
                      ticking = false;
                    });

                    ticking = true;
                  }
                });
                JS);
            $jOpts = Json::encode($options);

            $view->registerJs("$('#$id').scrollpager($jOpts, function(){
                listenToScroll = true;
            });");

            return Html::a($this->label, $this->pagination->createUrl($this->pagination->getPage() + 1), $this->options);
        }

        return '';
    }
}
