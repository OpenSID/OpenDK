<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-body">
                <?php
                    $entitiesToUtf8 = function($input) {
                        // http://php.net/manual/en/function.html-entity-decode.php#104617
                        return preg_replace_callback("/(&#[0-9]+;)/", function($m) { return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); }, $input);
                    };
                    $plainText = function($input) use ($entitiesToUtf8) {
                        return trim(html_entity_decode($entitiesToUtf8(strip_tags($input))));
                    };
                    $titlePlainText = function($input) use ($plainText) {
                        return '# '.$plainText($input);
                    };
                
                    ob_start();
                    phpinfo(-1);
                
                    $phpinfo = array('phpinfo' => array());

                    // Strip everything after the <h1>Configuration</h1> tag (other h1's)
                    if (!preg_match('#(.*<h1[^>]*>\s*Configuration.*)<h1#s', ob_get_clean(), $matches)) {
                        return array();
                    }
                
                    $input = $matches[1];
                    $matches = array();

                    if(preg_match_all(
                        '#(?:<h2.*?>(?:<a.*?>)?(.*?)(?:<\/a>)?<\/h2>)|'.
                        '(?:<tr.*?><t[hd].*?>(.*?)\s*</t[hd]>(?:<t[hd].*?>(.*?)\s*</t[hd]>(?:<t[hd].*?>(.*?)\s*</t[hd]>)?)?</tr>)#s',
                        $input,
                        $matches,
                        PREG_SET_ORDER
                    )) {
                        foreach ($matches as $match) {
                            $fn = strpos($match[0], '<th') === false ? $plainText : $titlePlainText;
                            if (strlen($match[1])) {
                                $phpinfo[$match[1]] = array();
                            } elseif (isset($match[3])) {
                                $keys1 = array_keys($phpinfo);
                                $phpinfo[end($keys1)][$fn($match[2])] = isset($match[4]) ? array($fn($match[3]), $fn($match[4])) : $fn($match[3]);
                            } else {
                                $keys1 = array_keys($phpinfo);
                                $phpinfo[end($keys1)][] = $fn($match[2]);
                            }

                        }
                    }
                ?>
                <?php $i = 0;?>
                <?php foreach ($phpinfo as $name => $section): ?>
                <?php $i++;?>
                <?php if ($i==1): ?>
                    <div class='table-responsive'>
                        <table class='table table-bordered dataTable table-hover'>
                    <?php else: ?>
                    <h3><?=$name?></h3>
                    <div class='table-responsive'>
                        <table class='table table-bordered dataTable table-hover'>
                <?php endif ?>
                <?php foreach ($section as $key => $val): ?>
                    <?php if (is_array($val)): ?>
                        <tr><td class="col-md-4 info"><?=$key?></td><td><?=$val[0]?></td><td><?=$val[1]?></td></tr>
                    <?php elseif (is_string($key)): ?>
                        <tr><td class="col-md-4 info"><?=$key?></td><td colspan='2'><?=$val?></td></tr>
                    <?php else: ?>
                        <tr><td class="btn-primary" colspan='3'><?=$val?></td></tr>
                    <?php endif; ?>
                <?php endforeach;?>
                    </table>
                </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</div>