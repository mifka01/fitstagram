<?php

namespace app\components;

use app\assets\ScrollPagerAsset;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/**
 * ScrollPager extends LoadMorePager to provide infinite scroll functionality
 * with improved performance and configuration options.
 *
 * @property int $scrollOffset Distance from bottom of page to trigger load (default: 1000px)
 * @property bool $autoInitialize Whether to automatically initialize scroll handling (default: true)
 * @property array $clientOptions Additional options passed to the JS scrollpager plugin
 */
class ScrollPager extends \sjaakp\loadmore\LoadMorePager
{
    /**
     * @var int pixels from bottom of page to trigger loading
     */
    public $scrollOffset = 1000;

    /**
     * @var bool whether to automatically initialize scroll handling
     */
    public $autoInitialize = true;

    /**
     * @var array<string, mixed> additional options passed to the JS scrollpager plugin
     */
    public $clientOptions = [];

    /**
     * @var array<string, mixed> default client options
     */
    private $defaultClientOptions = [
        // ms to wait between scroll events
        'throttleWait' => 100,
        // minimum pixels scrolled to trigger evaluation
        'minDistance' => 50,
    ];

    /**
     * {@inheritDoc}
     *
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        parent::init();

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        if (!is_int($this->scrollOffset) || $this->scrollOffset < 0) {
            throw new InvalidConfigException('scrollOffset must be a positive integer.');
        }
    }

    public function run(): string
    {
        $pageCount = $this->pagination->pageCount;
        if ($pageCount <= 1) {
            return '';
        }

        $view = $this->view;
        ScrollPagerAsset::register($view);

        $id = $this->options['id'];
        $options = $this->getClientOptions();
        $jsOptions = Json::encode($options);

        $this->registerScrollHandler($view, $id);
        $this->registerPagerInitialization($view, $id, $jsOptions);


        return Html::a(
            $this->label,
            $this->pagination->createUrl($this->pagination->getPage() + 1),
            $this->options
        );
    }

    /**
     * Gets merged client options
     *
     * @return array<string, mixed>
     */
    protected function getClientOptions(): array
    {
        return ArrayHelper::merge(
            $this->defaultClientOptions,
            [
                'pageCount' => $this->pagination->pageCount,
                'pageParam' => $this->pagination->pageParam,
                'scrollOffset' => $this->scrollOffset,
            ],
            $this->clientOptions
        );
    }

    /**
     * Registers the scroll handler JavaScript
     *
     * @param View $view
     * @param string $id
     * @return void
     */
    protected function registerScrollHandler($view, $id): void
    {
        $js = <<<JS
        (() => {
            const state = {
                lastScrollPosition: 0,
                ticking: false,
                isListening: true,
                loadStarted: false
            };

            const throttle = (func, wait) => {
                let timeout = null;
                let previous = 0;
                
                return (...args) => {
                    const now = Date.now();
                    const remaining = wait - (now - previous);
                    
                    if (remaining <= 0) {
                        if (timeout) {
                            clearTimeout(timeout);
                            timeout = null;
                        }
                        previous = now;
                        func.apply(null, args);
                    } else if (!timeout) {
                        timeout = setTimeout(() => {
                            previous = Date.now();
                            timeout = null;
                            func.apply(null, args);
                        }, remaining);
                    }
                };
            };

            const handleScroll = throttle((scrollPos) => {
                if (!state.isListening || state.loadStarted) return;
                
                const scrollBottom = scrollPos + window.innerHeight;
                const docHeight = document.documentElement.scrollHeight;
                const loadMoreButton = document.getElementById('$id');
                
                if (loadMoreButton && 
                    scrollBottom >= docHeight - window.scrollPagerOptions.scrollOffset) {
                    state.loadStarted = true;
                    state.isListening = false;
                    loadMoreButton.click();
                }
            }, window.scrollPagerOptions?.throttleWait || {$this->defaultClientOptions['throttleWait']});

            const scrollHandler = () => {
                state.lastScrollPosition = window.scrollY;
                
                if (!state.ticking) {
                    window.requestAnimationFrame(() => {
                        handleScroll(state.lastScrollPosition);
                        state.ticking = false;
                    });
                    state.ticking = true;
                }
            };

            // Expose methods to window for external control
            window.ScrollPager = {
                enable: () => {
                    state.isListening = true;
                    state.loadStarted = false;
                },
                disable: () => {
                    state.isListening = false;
                },
                destroy: () => {
                    document.removeEventListener('scroll', scrollHandler);
                    state.isListening = false;
                }
            };

            document.addEventListener('scroll', scrollHandler);
        })();
        JS;

        $view->registerJs($js, View::POS_END);
    }

    /**
     * Registers the pager initialization JavaScript
     *
     * @param View $view
     * @param string $id
     * @param string $options
     * @return void
     */
    protected function registerPagerInitialization($view, $id, $options): void
    {
        $js = <<<JS
            window.scrollPagerOptions = $options;
            $('#$id').scrollpager(window.scrollPagerOptions, function() {
                window.ScrollPager.enable();
            });
        JS;

        $view->registerJs($js, View::POS_READY);
    }
}
