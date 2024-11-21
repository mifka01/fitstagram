<?php
namespace app\widgets;

use yii\base\Widget;
use yii\data\Sort;
use yii\helpers\ArrayHelper;

class SortWidget extends Widget
{
    public Sort $sort;

    /**
     * @var array<string, string>
     */
    public array $containerOptions = [];

    /**
     * @var array<string, string>
     */
    public array $linkOptions = [];

    /**
     * @var array<string, string>
     */
    public array $activeLinkOptions = [];

    /**
     * Allow both ways sorting
     * default is true
     *
     * @var bool
     */
    public bool $allowBothWays = true;

    /**
     * Default sort attribute
     * default is false
     *
     * @var string|false
     */
    public string|false $defaultSort = false;

    private string $defaultContainerClass = 'sort-widget';

    private string $defaultLinkClass = 'sort-link';

    private string $defaultActiveLinkClass = 'sort-link-active';

    public function run(): string
    {
        $links = array_map(function ($name) {
            $isSortActive = $this->isSortActive($name);
            $options = $isSortActive ? $this->activeLinkOptions : $this->linkOptions;
        
            $link = $this->sort->link($name, $options);
        
            if (!$this->allowBothWays) {
                $link = $this->normalizeSortLink($link);
            }
        
            return $link;
        }, array_keys($this->sort->attributes));

        return $this->render('_sort', [
        'containerOptions' => $this->containerOptions,
        'links' => $links,
        ]);
    }

    /**
     * Determines if the current sort is active for a given attribute
     *
     * @param string $name Attribute name
     * @return bool Whether the sort is active
     */
    private function isSortActive(string $name): bool
    {
        $sortParam = \Yii::$app->request->get('sort');
    
        return $sortParam === $name
        || $sortParam === '-' . $name
        || ($sortParam === null && (
            $this->defaultSort === $name
            || $this->defaultSort === '-' . $name
        ));
    }

    /**
     * Normalizes sort link by removing negative sort indicators if needed
     *
     * @param string $link Original sort link
     * @return string Normalized sort link
     */
    private function normalizeSortLink(string $link): string
    {
        return str_replace('sort=-', 'sort=', $link);
    }

    public function init():void
    {

        if (empty($this->activeLinkOptions)) {
            $this->activeLinkOptions = [
                'style' => 'font-weight: bold;'
            ];
        }

        $existingContainerClasses = ArrayHelper::getValue($this->containerOptions, 'class', '');
        $this->containerOptions['class'] = trim($this->defaultContainerClass . ' ' . $existingContainerClasses);

        $existingLinkClasses = ArrayHelper::getValue($this->linkOptions, 'class', '');
        $this->linkOptions['class'] = trim($this->defaultLinkClass . $existingLinkClasses);

        $existingActiveLinkClasses = ArrayHelper::getValue($this->activeLinkOptions, 'class', '');
        $this->activeLinkOptions['class'] = trim($this->defaultActiveLinkClass . ' ' . $existingActiveLinkClasses);



        parent::init();
    }
}
