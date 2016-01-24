<?php 

namespace Queueless\Services\Navigation;

class Builder
{
    /**
     * The name of the organisation.
     *
     * @var string
     */
    protected $organisation;

    /**
     * Build the HTML navigation from the given config key.
     *
     * @param  string $url
     * @param  string $type
     * @return string
     */
    public function make($organisation, $url, $type = 'adminmenu')
    {
        $this->organisation = $organisation;

        $menu      = $this->getNavigationConfig($type);
        $html      = '';
        $hasActive = false;

        foreach ($menu as $item) {
            $isActive = false;

            if (! $hasActive && $this->isActiveItem($item, $url)) {
                $isActive = $hasActive = true;
            }

            $html .= $this->getNavigationItem($item, $isActive);
        }
        return $html;
    }

    /**
     * Load the navigation config for the given type.
     *
     * @param  string  $type
     * @return array
     */
    protected function getNavigationConfig($type)
    {
        return config('navigation.' . $type);
    }

    /**
     * Determine whether the given item is currently active.
     * @param  array   $item
     * @param  string  $url
     * @return bool
     */
    protected function isActiveItem(array $item, $url)
    {
        foreach ($item['active'] as $active) {
            if (str_is($active, $url)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get a parsed HTML navigation list item for the given item.
     *
     * @param  array  $item
     * @param  bool   $isActive
     * @return string
     */
    protected function getNavigationItem(array $item, $isActive)
    {
        $anchor = $this->getItemAnchor($item, $isActive);

        return $this->wrapAnchor($anchor);
    }

    /**
     * Get the HTML anchor link for the given item.
     *
     * @param  array  $item
     * @return string
     */
    protected function getItemAnchor(array $item, $isActive)
    {
        if(isset($item['glyphicon']))
            $inner = '<i class="'.$item['glyphicon'].'"></i><span class="sidebar-mini-hide"> ';
        else
            $inner = '';
            
        $class = $isActive ? ' class="active"' : '';
        if($this->organisation)
            $anchor = '<a'. $class . ' href="'.route($item['route'],$this->organisation).'">'.$inner.$item['label'].'</a>';
        else
            $anchor = '<a'. $class . ' href="'.route($item['route']).'">'.$inner.$item['label'].'</a>';
        return $anchor;
    }

    /**
     * Wrap the given anchor in a list item.
     *
     * @param  string  $anchor
     * @param  bool    $isActive
     * @return string
     */
    protected function wrapAnchor($anchor)
    {
        return '<li>' . $anchor . '</li>';
    }
}