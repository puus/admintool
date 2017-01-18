<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php ob_start(); ?>

<?php
$content = ob_get_clean();

$template = $this->load->view('framework/main.php', [
    'title'       => 'Users',
    'pagetitle'   => 'Users',
    'breadcrumbs' => [],
    'section' => 'Users',
    'content' => $content
]);
?>
