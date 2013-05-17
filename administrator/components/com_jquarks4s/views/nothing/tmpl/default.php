<!-- empty view displayed after popup process - with possibility of redirection -->
<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
        $type = JRequest::getInt('redir_type', 0);
        if ($type == 0) {
            define('ANALYSIS_URL', '');
        }
        elseif ($type == 1) {
            define('ANALYSIS_URL', 'index.php?option=com_jquarks4s&controller=analysis');
        }
?>

<script type="text/javascript">
    
    function redirect(url)
    {
        if (url != '') {
                window.parent.location.replace(url);
            }
    }

    function closeIframe()
    {
            new Ajax('index.php',
            {
                update:'',
                method:'post',
                data: '',
                onRequest : function()
                {
                    doc = window.parent.document.getElementById('sbox-window').close();
                    redirect('<?php echo ANALYSIS_URL; ?>');
                }
            }).request();
    }

    window.addEvent('domready', function(){ closeIframe(); });
</script>
