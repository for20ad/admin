<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();


    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aOrdIdx          = _elm( $view_datas, 'ordIdx' );
    $aDeliveryInfo    = _elm( $view_datas, 'trackingInfo', [] );
?>


<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_memo_register', 'autocomplete' => 'off']); ?>

<div class="card-body" style="border:1px solid #ccc;border-radius:5px; padding:20px;background-color: #f4f4f4;">
        <?php
        if( empty( $aDeliveryInfo ) === false):
        ?>
        <div class="timeline">
        <?php
            foreach( $aDeliveryInfo as $aKey => $vData ):
        ?>
            <div class="timeline-item">
                <div class="circle"></div>
                <div class="text">
                    <?php echo empty( _elm( $vData, 'date' ) ) === false ? _elm( $vData, 'date' ) : '' ?>
                    <?php echo empty( _elm( $vData, 'deliveryStatus' ) ) === false ? '<br>'._elm( $vData, 'deliveryStatus' ) : '' ?>
                    <?php echo empty( _elm( $vData, 'businessSite' ) ) === false ? '<br>'._elm( $vData, 'businessSite' ) : '' ?>
                    <?php echo empty( _elm( $vData, 'deliveryDetails' ) ) === false ? '<br>'._elm( $vData, 'deliveryDetails' ) : '' ?>
                    <?php echo empty( _elm( $vData, 'responsibleStaff' ) ) === false ? '<br>'._elm( $vData, 'responsibleStaff' ) : '' ?>
                    <?php echo empty( _elm( $vData, 'receiver' ) ) === false ? '<br>'._elm( $vData, 'receiver' ) : '' ?>
                    <?php echo empty( _elm( $vData, 'branchOffice' ) ) === false ? '<br> 영업소정보: '._elm( $vData, 'branchOffice' ).'('._elm( $vData, 'branchContact' ).')' : '' ?>

                </div>
            </div>

        <?php
            endforeach;
        ?>
        </div>
        <?php
        else:
        ?>
        <div>추적 가능한 데이터가 없습니다.</div>
        <?php
        endif;
        ?>

</div>

