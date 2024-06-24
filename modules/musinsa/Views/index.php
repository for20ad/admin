<?php
    if( isset( $pageDatas ) === false   ){
        $pageDatas = [];
    }
    $lists = _elm( $pageDatas, 'lists' );

    echo '<pre>';
    print_r( $lists  );
    echo '</pre>';
?>
<div>

</div>