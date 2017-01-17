<?php
    $menu = [
        'main navigation' => [
            'Dashboard' => [
                'link' => '/dashboard',
                'icon' => 'fa fa-dashboard'
            ],
            'Samples' => [
                'link' => '/sample',
                'icon' => 'fa fa-book'
            ],
            'More Samples' => [
                'link' => '/sample2',
                'icon' => 'fa fa-question-circle-o'
            ]
        ]
    ];
?>

<ul class="sidebar-menu">
  <li class="header">MAIN NAVIGATION</li>

  <?php foreach($menu['main navigation'] as $title => $item): ?>
    <li class="<?= array_key_exists('submenu', $item) ? 'treeview' : '' ?> <?= isset($section) && $section == $title ? 'active' : '' ?>">
        <a href="<?= $item['link'] ?>">
            <i class="<?= $item['icon'] ?>"></i>
            <span><?= $title ?></span>
            <?php if(array_key_exists('submenu', $item)): ?>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
            <?php endif; ?>
        </a>
        <?php if(array_key_exists('submenu', $item)): ?>
            <ul class="treeview-menu">
                <?php foreach($item['submenu'] as $subtitle => $subitem): ?>
                    <li><a href="<?= $subitem['link'] ?>"><i class="<?= $subitem['icon'] ?>"></i> <span><?= $subtitle ?></span></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </li>
  <?php endforeach; ?>
</ul>
