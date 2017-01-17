<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php ob_start(); ?>

<div>hi</div>

<?php
$content = ob_get_clean();

$template = $this->load->view('partials/framework.php', [
    'title'       => 'Add Course',
    'pagetitle'   => 'Add Course',
    'breadcrumbs' => [
        [
            'link'  => '/courses',
            'title' => 'Courses'
        ],
        [
            'link'  => '/courses/add',
            'title' => 'Add Course'
        ]
    ],
    'section' => 'Courses',
    'content' => $content
]);
?>
